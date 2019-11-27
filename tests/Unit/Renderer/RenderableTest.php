<?php

namespace OceanDBA\Graphitti\Tests\Unit\Renderer;

use OceanDBA\Graphitti\Metrics\Target;
use OceanDBA\Graphitti\Tests\TestCase;
use OceanDBA\Graphitti\Tests\Fixtures\Renderable\Renderer;

class RenderableTest extends TestCase
{
    public function test_it_can_swap_host()
    {
        $reflectedClass = new \ReflectionClass(Renderer::class);
        $reflectedProp = $reflectedClass->getProperty('host');
        $reflectedProp->setAccessible(true);

        /** @var Renderer $renderer */
        $renderer = app(Renderer::class);
        $this->assertEquals('https://graphite.example.com', $reflectedProp->getValue($renderer));

        $renderer->host('graphite2');
        $this->assertEquals('https://graphite2.example.com', $reflectedProp->getValue($renderer));
    }

    public function test_add_target()
    {
        /** @var Renderer $renderer */
        $renderer = app(Renderer::class);

        $this->assertCount(0, $renderer->parameters());

        $renderer->addTarget(Target::make('server.web1.load', 'Server-load'));

        $this->assertCount(1, $renderer->parameters());
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('graphitti.default', 'graphite');
        $app['config']->set('graphitti.hosts', [
            'graphite'  => 'https://graphite.example.com',
            'graphite2' => 'https://graphite2.example.com',
        ]);
    }
}
