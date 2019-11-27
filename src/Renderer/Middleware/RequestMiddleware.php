<?php

namespace OceanDBA\Graphitti\Renderer\Middleware;

use OceanDBA\Graphitti\Concerns\Makable;
use Psr\Http\Message\RequestInterface;

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
    public abstract function handle(RequestInterface $request): RequestInterface;
}
