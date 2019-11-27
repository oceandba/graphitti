<?php

namespace OceanDBA\Graphitti\Metrics\TimeRange;

use Illuminate\Support\Carbon;
use Illuminate\Support\Traits\Macroable;
use OceanDBA\Graphitti\Concerns\Makable;
use OceanDBA\Graphitti\Contracts\Metric;
use OceanDBA\Graphitti\Contracts\UrlParameter;

abstract class Time implements UrlParameter, Metric
{
    use Macroable, Makable;

    /**
     * The absolute or relative time.
     *
     * @var string|Carbon
     */
    protected $time;

    /**
     * Time constructor.
     *
     * @param $time
     */
    public function __construct($time)
    {
        $this->time = $time;
    }

    /**
     * Get the current value of the parameter as string for Graphite.
     *
     * @return string
     */
    public function value(): string
    {
        if ($this->time instanceof \Carbon\Carbon) {
            return $this->time->format('H:i_Ymd');
        }

        return $this->time;
    }
}
