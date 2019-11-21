<?php

namespace OceanDBA\Graphitti\Renderer;

use OceanDBA\Graphitti\Contracts\TimeSeries;
use OceanDBA\Graphitti\Series\DataPointsCollection;
use OceanDBA\Graphitti\Renderer\Middleware\ValidateResponse;
use OceanDBA\Graphitti\Renderer\Middleware\AddJsonHeadersToRequest;

class Points extends Renderable
{
    /**
     * The format to get data in.
     *
     * @var string
     */
    public $dataDisplayFormat = 'json';

    /**
     * Return all request middlewares for the given request.
     *
     * @return array
     */
    public function requestMiddlewares(): array
    {
        return [
            AddJsonHeadersToRequest::make(),
        ];
    }

    /**
     * Return all response middlewares for the given response.
     *
     * @return array
     */
    public function responseMiddlewares(): array
    {
        return [
            ValidateResponse::make(),
        ];
    }

    /**
     * Renders the Builder as a TimeSeries instance.
     *
     * @return TimeSeries
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function render(): TimeSeries
    {
        return DataPointsCollection::createFrom($this->send(), $this->targets());
    }
}
