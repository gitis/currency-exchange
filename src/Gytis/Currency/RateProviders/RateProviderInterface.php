<?php namespace Gytis\Currency\RateProviders;

interface RateProviderInterface{

    /**
     * Returns the exchange rate for currency pair
     *
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency);

    /**
     * Returns the name of the currency exchange
     *
     * @return string
     */
    public function getName();
}