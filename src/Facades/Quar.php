<?php

namespace tbQuar\Facades;

use Illuminate\Support\Facades\Facade;
use tbQuar\Generate;

class Quar extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        self::clearResolvedInstance(Generate::class);

        return Generate::class;
    }
}
