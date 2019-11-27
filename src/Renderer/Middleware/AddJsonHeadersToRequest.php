<?php

namespace OceanDBA\Graphitti\Renderer\Middleware;

use Psr\Http\Message\RequestInterface;

class AddJsonHeadersToRequest extends RequestMiddleware
{
    /**
     * Handle the middleware.
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function handle(RequestInterface $request): RequestInterface
    {
        $request->withHeader('Accept', 'application/json');
        $request->withHeader('Content-Type', 'application/json');

        return $request;
    }
}
