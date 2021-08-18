<?php

namespace asimshazad\simplepanel\Facades;

use Illuminate\Support\Facades\Facade;

class SimpleControlPanel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'simplecontrolpanel';
    }
}
