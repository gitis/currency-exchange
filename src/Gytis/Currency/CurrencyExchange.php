<?php namespace Gytis\Currency;

use Gytis\Currency\RateProviders\RateProviderInterface;
use Illuminate\Config\Repository as Config;

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
    /**
     * @var Config
     */
    protected $config;


    function __construct(RateProviderManager $rateProviderManager, Config $config)
    {
        $this->rateProviderManager = $rateProviderManager;
        $this->config = $config;

        //initialize providers based on configuration
        $providerArray = $this->config->get('currency::providers');

        if(!empty($providerArray)) {
            foreach ($providerArray as $provider) {
                $this->providers[] = $this->rateProviderManager->driver((string)$provider);
            }
        }else{
            throw new \RuntimeException('No providers available in the configuration file');
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

        foreach ($this->providers as $provider) {
            $rates[(string)$provider->getName()] = $provider->getRate($baseCurrency, $compCurrency);
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