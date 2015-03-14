<?php namespace Gytis\Currency;

use Gytis\Currency\RateProviders\ECBProvider;
use Gytis\Currency\RateProviders\GoogleProvider;
use Gytis\Currency\RateProviders\OpenExchangeRateProvider;
use Gytis\Currency\RateProviders\RateProviderInterface;
use Illuminate\Support\Manager;
use Illuminate\Support\Facades\Config;

class RateProviderManager extends Manager{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return Config::get('currency::default_provider');
    }


    /**
     * Create an instance of the ECB provider.
     *
     * @return RateProviderInterface
     */
    public function createEcbDriver()
    {
        return new ECBProvider();
    }

    /**
     * Create an instance of the OpenExchangeRate provider.
     *
     * @return RateProviderInterface
     */
    public function createOerDriver()
    {
        return new OpenExchangeRateProvider(Config::get('currency::oer_app_id'));
    }

    /**
     * Create an instance of the Google provider.
     *
     * @return RateProviderInterface
     */
    public function createGoogleDriver()
    {
        return new GoogleProvider();
    }
}

