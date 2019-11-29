<?php

namespace OceanDBA\Graphitti\Series;

use Illuminate\Support\Carbon;
use Illuminate\Support\Traits\Macroable;

class Point
{
    use Macroable;

    /**
     * Time at which the point occurred.
     *
     * @var Carbon
     */
    protected $time;

    /**
     * Value of the point.
     *
     * @var float
     */
    protected $value;

    /**
     * Point constructor.
     *
     * @param Carbon $time
     * @param float  $value
     */
    public function __construct(Carbon $time, float $value)
    {
        $this->time = $time;
        $this->value = $value;
    }

    /**
     * Get the time for this Point.
     *
     * @return Carbon
     */
    public function time(): Carbon
    {
        return $this->time;
    }

    /**
     * Get the value of the Point.
     *
     * @return float
     */
    public function value(): float
    {
        return $this->value;
    }

    /**
     * Create a new Point using raw values.
     *
     * @param int   $timestamp
     * @param float $value
     *
     * @return Point
     */
    public static function make(int $timestamp, float $value): self
    {
        $time = Carbon::createFromTimestamp($timestamp);
        $value = round($value, 2);

        return new static($time, $value);
    }
}
