<?php

namespace OceanDBA\Graphitti\Renderer\Middleware;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use OceanDBA\Graphitti\Exceptions\ApiException;

class ValidateResponse extends ResponseMiddleware
{
    /**
     * Handle the middleware.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     *
     * @throws ApiException
     */
    public function handle(ResponseInterface $response): ResponseInterface
    {
        if ($this->isSuccessful($response)) {
            return $response;
        }

        $this->handleErrorResponse($response);
    }

    /**
     * Check if response was successful.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    protected function isSuccessful(ResponseInterface $response)
    {
        return $response->getStatusCode() < Response::HTTP_BAD_REQUEST;
    }

    /**
     * Handle error from the response.
     *
     * @param ResponseInterface $response
     *
     * @throws ApiException
     */
    protected function handleErrorResponse(ResponseInterface $response)
    {
        throw ApiException::make((string) $response->getBody());
    }
}
