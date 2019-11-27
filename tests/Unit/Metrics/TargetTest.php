<?php

namespace OceanDBA\Graphitti\Tests\Unit\Metrics;

use OceanDBA\Graphitti\Metrics\Target;
use OceanDBA\Graphitti\Tests\TestCase;

class TargetTest extends TestCase
{
    public function test_it_can_apply_methods()
    {
        $target = Target::make('server.web1.load', 'Server-load');
        $this->assertEquals('server.web1.load', $target->value());

        $target->apply('derivative');
        $target->apply('summarize', '1min');

        $this->assertEquals('summarize(derivative(server.web1.load),"1min")', $target->value());
    }

    public function test_it_can_proxy_method_to_apply_methods()
    {
        $target = Target::make('server.web1.load', 'Server-load');
        $this->assertEquals('server.web1.load', $target->value());

        $target->derivative();
        $target->summarize('1min');

        $this->assertEquals('summarize(derivative(server.web1.load),"1min")', $target->value());
    }

    public function test_it_can_render_as_query_string_parameter()
    {
        $target = Target::make('server.web1.load', 'Server-load');
        $target->derivative();
        $target->summarize('1min');

        $this->assertEquals('target=summarize(derivative(server.web1.load),"1min")', $target->render());
    }
}
