<?php

namespace OceanDBA\Graphitti\Contracts;

interface UrlParameter
{
    /**
     * Get the current value of the parameter as string for Graphite.
     *
     * @return string
     */
    public function value(): string;

    /**
     * Renders parameter as query string for making request.
     *
     * @return string
     */
    public function render(): string;
}
