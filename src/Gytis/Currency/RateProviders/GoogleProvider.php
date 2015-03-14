<?php namespace Gytis\Currency\RateProviders;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class GoogleProvider implements RateProviderInterface{

    /**
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency)
    {
        $rates = Cache::remember($this->getName(), Config::get('currency::cache_duration'), function() use ($baseCurrency, $compCurrency){
            $url = 'http://rate-exchange.appspot.com/currency?from=' . $baseCurrency . '&to=' . $compCurrency . '&q=1}';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $json_result = curl_exec($ch);
            curl_close($ch);

            $rates = json_decode($json_result,true);
            if($rates === null) throw new \Exception('Received invalid JSON from ' . $this->getName());

            return $rates;
        });

        return array_get($rates, 'rate', 0);
    }

    /**
     * Returns the name of the currency exchange
     *
     * @return string
     */
    public function getName()
    {
        return 'Google';
    }
}