<?php

namespace OceanDBA\Graphitti\Renderer;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Psr\Http\Message\UriInterface;
use OceanDBA\Graphitti\Metrics\Target;
use Psr\Http\Message\RequestInterface;
use OceanDBA\Graphitti\Graph\Parameter;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Traits\Macroable;
use OceanDBA\Graphitti\Contracts\TimeSeries;
use OceanDBA\Graphitti\Contracts\UrlParameter;
use OceanDBA\Graphitti\Metrics\TimeRange\From;
use OceanDBA\Graphitti\Metrics\TimeRange\Until;
use OceanDBA\Graphitti\Exceptions\InvalidGraphiteHost;

abstract class Renderable
{
    use Macroable;

    /**
     * The format to get data in.
     *
     * @var string
     */
    protected $dataDisplayFormat = 'raw';

    /**
     * Host Url to use for rendering.
     *
     * @var string
     */
    protected $host;

    /**
     * List of parameters to be used.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $parameters;

    /**
     * The handler to use for making requests.
     *
     * @var mixed
     */
    protected $handler;

    /**
     * Renderable constructor.
     *
     * @param mixed $handler
     */
    public function __construct($handler = null)
    {
        $this->resetParameters();
        $this->handler = $handler;
    }

    /**
     * Renders as a TimeSeries instance.
     *
     * @return TimeSeries
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    abstract public function render(): TimeSeries;

    /**
     * Return all request middlewares for the given request.
     *
     * @return array
     */
    abstract public function requestMiddlewares(): array;

    /**
     * Return all response middlewares for the given response.
     *
     * @return array
     */
    abstract public function responseMiddlewares(): array;

    /**
     * Return all parameters for the current renderer..
     *
     * @return Collection
     */
    public function parameters(): Collection
    {
        return $this->parameters;
    }

    /**
     * The format into which we should get data.
     *
     * @return UrlParameter
     */
    public function dataDisplayFormat(): UrlParameter
    {
        return Parameter::make('format', $this->dataDisplayFormat);
    }

    /**
     * Reset all parameters.
     *
     * @return Renderable
     */
    public function resetParameters(): Renderable
    {
        $this->parameters = collect();

        return $this;
    }

    /**
     * Add a new Target.
     *
     * @param Target $target
     *
     * @return Renderable
     */
    public function addTarget(Target $target): Renderable
    {
        return $this->addParameter($target);
    }

    /**
     * Add a From time.
     *
     * @param Carbon|string $time
     *
     * @return Renderable
     */
    public function from($time): Renderable
    {
        return $this->addParameter(From::make($time));
    }

    /**
     * Add an Until time.
     *
     * @param Carbon|string $time
     *
     * @return Renderable
     */
    public function until($time): Renderable
    {
        return $this->addParameter(Until::make($time));
    }

    /**
     * Add a new parameter.
     *
     * @param UrlParameter|string $parameter
     * @param mixed               $value
     *
     * @return Renderable
     */
    public function addParameter($parameter, $value = null): Renderable
    {
        if ($parameter instanceof UrlParameter) {
            $this->parameters->add($parameter);
            return $this;
        }

        if (is_null($value)) {
            throw new \InvalidArgumentException('Parameter needs a value.');
        }

        $this->parameters->add(Parameter::make($parameter, $value));

        return $this;
    }

    /**
     * Update the host.
     *
     * @param string $name
     *
     * @return Renderable
     *
     * @throws InvalidGraphiteHost
     */
    public function host(string $name): Renderable
    {
        $this->host = config("graphitti.hosts.{$name}");

        if (is_null($this->host)) {
            throw InvalidGraphiteHost::unknownHost($name);
        }

        return $this;
    }

    /**
     * Update the Handler to use when making requests.
     *
     * @param mixed $handler
     *
     * @return Renderable
     */
    public function handler($handler): Renderable
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Prepare the Handler stack for the Client.
     *
     * @return HandlerStack
     */
    protected function handlerStack(): HandlerStack
    {
        /** @var HandlerStack $stack */
        $stack = app(HandlerStack::class);
        $stack->setHandler($this->handler);

        $this->runThroughRequestMiddlewares($stack);
        $this->runThroughResponseMiddlewares($stack);

        return $stack;
    }

    /**
     * Prepare the client for making requests.
     *
     * @return Client
     */
    public function client(): Client
    {
        return new Client([
            'handler' => $this->handlerStack(),
        ]);
    }

    /**
     * Build and return the URI for making requests.
     *
     * @return UriInterface
     */
    protected function uri(): UriInterface
    {
        return (new Uri($this->host))->withPath('/render')
                                     ->withQuery((clone $this->parameters)->add($this->dataDisplayFormat())->map->render()->implode('&'));
    }

    /**
     * Build the request.
     *
     * @return RequestInterface
     */
    protected function request(): RequestInterface
    {
        return new Request('GET', $this->uri());
    }

    /**
     * Send request to render data.
     *
     * @return ResponseInterface
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function send()
    {
        return $this->client()->send($this->request());
    }

    /**
     * Add all request middlewares to the stack.
     *
     * @param HandlerStack $stack
     *
     * @return void
     */
    protected function runThroughRequestMiddlewares(HandlerStack $stack)
    {
        foreach ($this->requestMiddlewares() as $middleware) {
            $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($middleware) {
                return $middleware->handle($request);
            }));
        }
    }

    /**
     * Add all response middlewares to the stack.
     *
     * @param HandlerStack $stack
     *
     * @return void
     */
    protected function runThroughResponseMiddlewares($stack): void
    {
        foreach ($this->responseMiddlewares() as $middleware) {
            $stack->push(Middleware::mapResponse(function (ResponseInterface $response) use ($middleware) {
                return $middleware->handle($response);
            }));
        }
    }

    /**
     * Get the targets which have been used.
     *
     * @return Collection
     */
    protected function targets(): Collection
    {
        return $this->parameters->filter(function ($parameter) {
            return $parameter instanceof Target;
        });
    }
}
