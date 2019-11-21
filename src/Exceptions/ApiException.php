<?php

namespace OceanDBA\Graphitti\Exceptions;

use Exception;
use OceanDBA\Graphitti\Concerns\Makable;

class ApiException extends Exception
{
    use Makable;
}
