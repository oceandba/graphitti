<?php

namespace OceanDBA\Graphitti\Renderer\Middleware;

use Psr\Http\Message\ResponseInterface;
use OceanDBA\Graphitti\Concerns\Makable;

abstract class ResponseMiddleware
{
    use Makable;

    /**
     * Handle the middleware.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    abstract public function handle(ResponseInterface $response): ResponseInterface;
}
