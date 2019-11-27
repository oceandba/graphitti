<?php

namespace OceanDBA\Graphitti\Tests\Fixtures\Renderable;

use OceanDBA\Graphitti\Renderer\Renderable;
use OceanDBA\Graphitti\Contracts\TimeSeries;
use OceanDBA\Graphitti\Tests\Fixtures\Series\NullSeries;

class Renderer extends Renderable
{
    /**
     * Renders as a TimeSeries instance.
     *
     * @return TimeSeries
     */
    public function render(): TimeSeries
    {
        return app(NullSeries::class);
    }

    /**
     * Return all request middlewares for the given request.
     *
     * @return array
     */
    public function requestMiddlewares(): array
    {
        return [];
    }

    /**
     * Return all response middlewares for the given response.
     *
     * @return array
     */
    public function responseMiddlewares(): array
    {
        return [];
    }
}
