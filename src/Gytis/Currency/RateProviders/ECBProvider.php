<?php namespace Gytis\Currency\RateProviders;

use Gytis\Currency\DataFetchers\DataFetcherInterface;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class ECBProvider implements RateProviderInterface{

    private static $URL = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    protected $config;
    protected $cache;
    protected $dataFetcher;

    function __construct(Config $config,
                         Cache $cache,
                         DataFetcherInterface $dataFetcher)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->dataFetcher = $dataFetcher;
    }

    /**
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency)
    {
        $results = $this->cache->remember($this->getName(), $this->config->get('currency::cache_duration'), function(){
            return $this->dataFetcher->getAssocArray(static::$URL);
        });

        $rates = $this->parseResults($results);
        if($baseCurrency == 'EUR'){
            return array_get($rates, $compCurrency, 0);
        }else if($compCurrency == 'EUR') {
            return 1 / (float)array_get($rates, $baseCurrency, 0);
        }else{
            $cur1 = array_get($rates, $baseCurrency, 0);
            $cur2 = array_get($rates, $compCurrency, 0);

            if($cur1 > 0 && $cur2 > 0){
                return $cur2 / $cur1;
            }else{
                return 0;
            }
        }
    }

    private function parseResults($results){
        $parsedArray = array();
        foreach(array_get($results,'Cube.Cube.Cube') as $results){
            $parsedLine = array_get($results,'@attributes');
            $parsedArray[$parsedLine['currency']] = (float)$parsedLine['rate'];
        }

        return $parsedArray;
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