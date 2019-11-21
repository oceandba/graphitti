<?php

namespace OceanDBA\Graphitti\Series;

use Illuminate\Support\Collection;
use OceanDBA\Graphitti\Metrics\Target;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Traits\Macroable;
use OceanDBA\Graphitti\Contracts\TimeSeries;

class DataPointsCollection extends Collection implements TimeSeries
{
    use Macroable;

    /**
     * Create a new DataPointsCollection using the response and targets data.
     *
     * @param ResponseInterface $response
     * @param Collection        $targets
     *
     * @return DataPointsCollection
     */
    public static function createFrom(ResponseInterface $response, Collection $targets): DataPointsCollection
    {
        $rawDatapoints = collect(json_decode($response->getBody()->getContents(), true));

        // There is a JSON decode error.
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Could not decode data from Graphite, malformed json.');
        }

        $items = [];

        /** @var Target $target */
        foreach ($targets as $target) {
            $items[] = DataPoints::make($target, $rawDatapoints->where('target', $target->value())->first());
        }

        return new static($items);
    }
}
