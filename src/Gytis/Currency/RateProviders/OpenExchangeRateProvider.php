<?php namespace Gytis\Currency\RateProviders;

use Gytis\Currency\DataFetchers\DataFetcherInterface;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class OpenExchangeRateProvider implements RateProviderInterface{

    /**
     * @var Config
     */
    protected  $config;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var DataFetcherInterface
     */
    protected $dataFetcherInterface;
    /**
     * @var OpenExchangeRateUrlBuilder
     */
    protected $urlBuilder;

    function __construct(Config $config,
                         Cache $cache,
                         DataFetcherInterface $dataFetcher,
                         OpenExchangeRateUrlBuilder $urlBuilder)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->dataFetcher = $dataFetcher;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency)
    {
        $url = $this->urlBuilder->make((string)$this->config->get('currency::oer_app_id'));

        $rates = $this->cache->remember($this->getName(), $this->config->get('currency::cache_duration'), function() use ($url){
            return $this->dataFetcher->getAssocArray($url);
        });

        if($baseCurrency == 'USD'){
            return array_get($rates, 'rates.'.$compCurrency, 0);
        }else if ($compCurrency == 'USD'){
            return 1 / (float) array_get($rates, 'rates.'.$baseCurrency, 0);
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