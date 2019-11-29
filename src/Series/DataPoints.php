<?php

namespace OceanDBA\Graphitti\Series;

use Illuminate\Support\Collection;
use OceanDBA\Graphitti\Metrics\Target;
use Illuminate\Support\Traits\Macroable;

class DataPoints
{
    use Macroable;

    /**
     * The Target for which this DataPoints
     * belongs to.
     *
     * @var Target
     */
    protected $target;

    /**
     * All the points contained within
     * the DataPoints.
     *
     * @var Collection
     */
    protected $points;

    /**
     * DataPoints constructor.
     *
     * @param Target     $target
     * @param Collection $points
     */
    public function __construct(Target $target, Collection $points)
    {
        $this->target = $target;
        $this->points = $points;
    }

    /**
     * Create a new DataPoints using raw values.
     *
     * @param Target $target
     * @param array  $data
     *
     * @return DataPoints
     */
    public static function make(Target $target, array $data = null): self
    {
        $data = $data ?? [];
        $points = collect($data['datapoints'] ?? [])->transform(function ($point) {
            return Point::make($point[1], $point[0] ?? 0.0);
        });

        return new static($target, $points);
    }

    /**
     * Returns the Target associated to the DataPoints.
     *
     * @return Target
     */
    public function target(): Target
    {
        return $this->target;
    }

    /**
     * Returns the points Collection.
     *
     * @return Collection
     */
    public function points(): Collection
    {
        return $this->points;
    }

    /**
     * Determine if the datapoints is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->points->isEmpty();
    }

    /**
     * Determine if the datapoints is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }
}
