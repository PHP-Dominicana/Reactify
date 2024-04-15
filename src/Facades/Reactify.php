<?php

namespace PHPDominicana\Reactify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PHPDominicana\Reactify\Reactify
 */
class Reactify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \PHPDominicana\Reactify\Reactify::class;
    }
}
