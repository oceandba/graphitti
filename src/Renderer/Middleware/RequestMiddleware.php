<?php

namespace OceanDBA\Graphitti\Renderer\Middleware;

use Psr\Http\Message\RequestInterface;
use OceanDBA\Graphitti\Concerns\Makable;

abstract class RequestMiddleware
{
    use Makable;

    /**
     * Handle the middleware.
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    abstract public function handle(RequestInterface $request): RequestInterface;
}
