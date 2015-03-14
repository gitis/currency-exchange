<?php namespace Gytis\Currency\RateProviders;

use Illuminate\Support\Facades\Cache;

class ECBProvider implements RateProviderInterface{

    /**
     * Fetches and save to cache xml element provided by url
     *
     * @param $url
     * @param $duration
     * @return \SimpleXMLElement
     */
    private function fetchAndSaveXmlData($url, $duration = 60){

        $XMLstring = Cache::remember($this->getName(),$duration,function() use ($url){
            //SimpleXMLElement object cannot be serialized, so we serialize it as a string instead.
            $content = simplexml_load_file($url);

            if($content === FALSE) throw new \Exception('Error parsing data from ' . $this->getName());

            return $content->asXML();
        });

        $XML = simplexml_load_string($XMLstring);
        return $XML;
    }


    /**
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency)
    {
        $XML = $this->fetchAndSaveXmlData('http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml', Config::get('currency::cache_duration'));

        if($baseCurrency == 'EUR'){
            foreach($XML->Cube->Cube->Cube as $rate){
                if($rate['currency'] == $compCurrency) return $rate['rate'];
            }
            return 0;
        }else{
            $compCurrencyRate = NULL;
            $baseCurrencyRate = NULL;

            foreach($XML->Cube->Cube->Cube as $rate){
                if($rate['currency'] == $compCurrency) $compCurrencyRate = $rate['rate'];
                if($rate['currency'] == $baseCurrency) $baseCurrencyRate = $rate['rate'];


                if(isset($compCurrencyRate) && isset($baseCurrencyRate)){
                    //we now have all data and can return calculated result
                    return (float)$compCurrencyRate / (float)$baseCurrencyRate;
                }
            }
            return 0;
        }
    }

    /**
     * Returns the name of the currency exchange
     *
     * @return string
     */
    public function getName()
    {
        return 'European Central Bank';
    }
}