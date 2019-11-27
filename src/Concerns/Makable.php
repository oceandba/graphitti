<?php

namespace OceanDBA\Graphitti\Concerns;

trait Makable
{
    /**
     * Creates and return a new instance
     * with the given parameters.
     *
     * @return self
     */
    public static function make()
    {
        return new static(...func_get_args());
    }
}
