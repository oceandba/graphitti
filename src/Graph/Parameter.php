<?php

namespace OceanDBA\Graphitti\Graph;

use OceanDBA\Graphitti\Concerns\Makable;
use OceanDBA\Graphitti\Contracts\UrlParameter;

class Parameter implements UrlParameter
{
    use Makable;

    /**
     * The key of the parameter.
     *
     * @var string
     */
    protected $key;

    /**
     * The value of the parameter.
     *
     * @var mixed
     */
    protected $parameter;

    /**
     * Parameter constructor.
     *
     * @param string $key
     * @param mixed  $parameter
     */
    public function __construct(string $key, $parameter)
    {
        $this->key = $key;
        $this->parameter = $parameter;
    }

    /**
     * Get the current value of the parameter as string for Graphite.
     *
     * @return string
     */
    public function value(): string
    {
        return strval($this->parameter);
    }

    /**
     * Renders parameter as query string for making request.
     *
     * @return string
     */
    public function render(): string
    {
        return "{$this->key}={$this->value()}";
    }
}
