<?php namespace Netsells\Marklang;

use Illuminate\Support\Facades\Facade;

class MarklangFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'marklang';
    }
}
