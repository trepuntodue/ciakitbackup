<?php

namespace PSW\Cinema\Film\Facades;

use Illuminate\Support\Facades\Facade;

class ReleaseImage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'releaseimage';
    }
}