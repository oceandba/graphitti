<?php

namespace OceanDBA\Graphitti\Metrics\TimeRange;

class From extends Time
{
    /**
     * Renders parameter as query string for making request.
     *
     * @return string
     */
    public function render(): string
    {
        return 'from=' . $this->value();
    }
}
