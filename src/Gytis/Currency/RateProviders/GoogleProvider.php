<?php namespace Gytis\Currency\RateProviders;

use Gytis\Currency\DataFetchers\DataFetcherInterface;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class GoogleProvider implements RateProviderInterface{

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
    protected $dataFetcher;
    /**
     * @var GoogleUrlBuilder
     */
    protected $urlBuilder;


    function __construct(Config $config,
                         Cache $cache,
                         DataFetcherInterface $dataFetcher,
                         GoogleUrlBuilder $urlBuilder)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->dataFetcher = $dataFetcher;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Returns exchange rate for provided currencies and saves it to cache.
     * default to 0 if requested currency is not found
     *
     * @param string $baseCurrency
     * @param string $compCurrency
     * @return double
     */
    public function getRate($baseCurrency, $compCurrency)
    {
        $url = $this->urlBuilder->make($baseCurrency,$compCurrency);

        $rates = $this->cache->remember((string)$baseCurrency.(string)$compCurrency.$this->getName(), $this->config->get('currency::cache_duration'), function() use ($url){
            return $this->dataFetcher->getAssocArray($url);
        });

        return array_get($rates, 'rate', 0); //defaults to 0 if requested currency is not found
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