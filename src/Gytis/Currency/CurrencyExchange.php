<?php namespace Gytis\Currency;

use Gytis\Currency\RateProviders\RateProviderInterface;
use Illuminate\Support\Facades\Config;

class CurrencyExchange{

    /**
     * Exchange rate providers array
     *
     * @var array
     */
    protected $providers;

    /**
     *
     * @var RateProviderManager
     */
    protected $rateProviderManager;


    function __construct(RateProviderManager $rateProviderManager)
    {
        $this->rateProviderManager = $rateProviderManager;

        //initialize providers based on configuration
        $providerArray = Config::get('currency::providers');
        foreach($providerArray as $provider) {
            $this->providers[] = $this->rateProviderManager->driver((string)$provider);
        }
    }

    /**
     * Returns an array of currency rates from various providers for a given currency pair
     *
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return array
     */
    public function getCurrencyRates($baseCurrency, $compCurrency)
    {
        $rates = array();

        foreach($this->providers as $provider){
                $rates[$provider->getName()] = $provider->getRate($baseCurrency, $compCurrency);
        }

        return $rates;
    }

    /**
     * Returns the best exchange rate for a given currency pair
     *
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return RateProviderInterface
     */
    public function getBestRateProvider($baseCurrency, $compCurrency)
    {
        $bestRate = 0;

        foreach($this->providers as $provider){
            $providerRate = $provider->getRate($baseCurrency, $compCurrency);

            if($providerRate > $bestRate){
                $bestRate = $providerRate;
                $bestProvider = $provider;
            }
        }

        return $bestProvider;
    }
}