<?php

namespace OceanDBA\Graphitti\Tests\Feature\Renderer;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use OceanDBA\Graphitti\Metrics\Target;
use OceanDBA\Graphitti\Tests\TestCase;
use OceanDBA\Graphitti\Renderer\Points;
use OceanDBA\Graphitti\Series\DataPointsCollection;

class PointsTest extends TestCase
{
    public function test_it_returns_datapoints_collection()
    {
        /** @var Points $points */
        $points = app(Points::class)->handler($this->mockPoints());
        $dataPointsCollection = $points->addTarget(Target::make('oceandba.server1.load', 'Server-load-1'))
                                       ->addTarget(Target::make('oceandba.server2.load', 'Server-load-2'))
                                       ->addTarget(Target::make('oceandba.server3.load', 'Server-load-3'))
                                       ->from('-1h')
                                       ->until('now')
                                       ->addParameter('maxDataPoints', 50)
                                       ->render();

        $this->assertInstanceOf(DataPointsCollection::class, $dataPointsCollection);
        $this->assertCount(3, $dataPointsCollection);
    }

    public function test_it_follows_defined_precision()
    {
        /** @var Points $points */
        $points = app(Points::class)->handler($this->mockPoints());
        $dataPointsCollection = $points->addTarget(Target::make('oceandba.server1.load', 'Server-load-1')->precision(2))
                                       ->addTarget(Target::make('oceandba.server2.load', 'Server-load-2'))
                                       ->from('-1h')
                                       ->until('now')
                                       ->addParameter('maxDataPoints', 50)
                                       ->render();

        $this->assertEquals(
            [0.54, 1.07, 0.0],
            $dataPointsCollection->first()->points()->map->value()->all()
        );

        $this->assertEquals(
            [0.48166666666666674, 0.6614285714285714, 0.7287499999999999],
            $dataPointsCollection->last()->points()->map->value()->all()
        );
    }

    /**
     * Return mocks of datapoints.
     *
     * @return MockHandler
     */
    protected function mockPoints()
    {
        return new MockHandler([
            new Response(200, [], file_get_contents(__DIR__.'/../../Fixtures/datapoints.json')),
        ]);
    }
}
