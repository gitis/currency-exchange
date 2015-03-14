<?php namespace Gytis\Currency\Facades;

use Illuminate\Support\Facades\Facade;

class CurrencyProvider extends Facade{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'RateProviderManager'; }
}