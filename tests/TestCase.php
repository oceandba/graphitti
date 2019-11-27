<?php

namespace OceanDBA\Graphitti\Tests;

use OceanDBA\Graphitti\GraphittiServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [GraphittiServiceProvider::class];
    }

    /**
     * Get package aliases.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'GraphitePoints' => \OceanDBA\Graphitti\Facades\GraphitePoints::class,
        ];
    }
}
