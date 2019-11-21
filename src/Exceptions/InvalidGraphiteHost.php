<?php

namespace OceanDBA\Graphitti\Exceptions;

use Exception;

class InvalidGraphiteHost extends Exception
{
    /**
     * Create a new UndefinedGraphiteHost instance.
     *
     * @param string $name
     *
     * @return InvalidGraphiteHost
     */
    public static function unknownHost(string $name)
    {
        return new static("Host {$name} is not defined in graphitti config");
    }
}
