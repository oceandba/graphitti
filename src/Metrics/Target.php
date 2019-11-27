<?php

namespace OceanDBA\Graphitti\Metrics;

use Illuminate\Support\Traits\Macroable;
use OceanDBA\Graphitti\Concerns\Makable;
use OceanDBA\Graphitti\Contracts\Metric;
use OceanDBA\Graphitti\Contracts\UrlParameter;

class Target implements UrlParameter, Metric
{
    use Makable, Macroable;

    /**
     * Name for the target.
     *
     * @var string
     */
    protected $name;

    /**
     * The current target value.
     *
     * @var string
     */
    protected $target;

    /**
     * Target constructor.
     *
     * @param string $target
     * @param string $name
     */
    public function __construct(string $target, string $name = null)
    {
        $this->target = $target;
        $this->name = $name;
    }

    /**
     * Applies a method on the target.
     *
     * @param string $method
     * @param mixed  ...$args
     *
     * @return Metric
     */
    public function apply(string $method, ...$args): Metric
    {
        $parameters = '';

        if (!empty($args)) {
            $parameters = collect($args)->transform(function ($item) {
                return '"' . $item . '"';
            })->implode(',');

            $parameters = ',' . $parameters;
        }

        $this->target = $method . '(' . $this->target . $parameters . ')';

        return $this;
    }

    /**
     * Get the current value of the parameter as string for Graphite.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->target;
    }

    /**
     * Renders parameter as query string for making request.
     *
     * @return string
     */
    public function render(): string
    {
        return 'target=' . $this->value();
    }

    /**
     * Proxy method call to apply method.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Metric
     */
    public function __call($name, $arguments)
    {
        return $this->apply($name, ...$arguments);
    }

    /**
     * Cast the target as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
