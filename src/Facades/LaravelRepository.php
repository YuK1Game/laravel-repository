<?php

namespace YuK1\LaravelRepository\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-repository';
    }
}
