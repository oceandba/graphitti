<?php

namespace OceanDBA\Graphitti\Metrics\TimeRange;

class Until extends Time
{
    /**
     * Renders parameter as query string for making request.
     *
     * @return string
     */
    public function render(): string
    {
        return 'until='.$this->value();
    }
}
