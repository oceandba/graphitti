<?php

namespace OceanDBA\Graphitti\Facades;

use Illuminate\Support\Facades\Facade;
use OceanDBA\Graphitti\Renderer\Points;

class GraphitePoints extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return Points::class;
    }
}
