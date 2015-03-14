<?php namespace Gytis\Currency\RateProviders;

use Illuminate\Support\Facades\Cache;

class OpenExchangeRateProvider implements RateProviderInterface{

    /**
     * @var string
     */
    protected static $BASE_URL = 'http://openexchangerates.org/api/latest.json';

    /**
     * @var string
     */
    protected $appID;

    /**
     * @param $appID
     */
    function __construct($appID)
    {
        $this->appID = $appID;
    }

    /**
     * Fetches and saves to cache json data
     *
     * @param $url
     * @param $duration
     * @return mixed
     */
    private function fetchAndSaveJsonData($url,$duration){
        return Cache::remember($this->getName(), $duration, function() use ($url){

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $json_result = curl_exec($ch);
            curl_close($ch);

            $rates = json_decode($json_result,true);
            if($rates === null) throw new \Exception('Received invalid JSON from ' . $this->getName());

            return $rates;
        });
    }

    /**
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency)
    {
        $rates = $this->fetchAndSaveJsonData(OpenExchangeRateProvider::$BASE_URL . '?app_id=' . $this->appID, Config::get('currency::cache_duration'));

            if($baseCurrency == 'USD'){
                return array_get($rates, 'rates.'.$compCurrency, 0);

            }else{
                $cur1 = array_get($rates, 'rates.'.$baseCurrency, 0);
                $cur2 = array_get($rates, 'rates.'.$compCurrency, 0);

                if($cur1 > 0 && $cur2 > 0){
                    return $cur2 / $cur1;
                }else{
                    return 0;
                }
            }
    }

    /**
     * Returns the name of the currency exchange
     *
     * @return string
     */
    public function getName()
    {
        return 'OpenExchangeRate';
    }
}