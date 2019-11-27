<?php

namespace OceanDBA\Graphitti\Series;

use Illuminate\Support\Carbon;

class Point
{
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
