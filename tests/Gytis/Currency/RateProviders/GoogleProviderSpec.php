<?php

namespace tests\Gytis\Currency\RateProviders;

use Gytis\Currency\DataFetchers\JsonDataFetcher;
use Gytis\Currency\RateProviders\GoogleUrlBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class GoogleProviderSpec extends ObjectBehavior
{
    function let(Config $config,
                 Cache $cache,
                 JsonDataFetcher $jsonDataFetcher,
                 GoogleUrlBuilder $urlBuilder)
    {
        $this->beConstructedWith($config,$cache,$jsonDataFetcher,$urlBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Gytis\Currency\RateProviders\GoogleProvider');
    }

    function it_returns_correct_rate(Config $config,
                                     Cache $cache,
                                     JsonDataFetcher $jsonDataFetcher,
                                     GoogleUrlBuilder $urlBuilder)
    {
        $result = ['rate' => 3.3];
        $baseCur = 'LTL';
        $compCur = 'GBP';
        $duration = 1;

        $config->get('currency::cache_duration')->willReturn($duration);
        $cache->remember($baseCur.$compCur.'Google',$duration,Argument::any())->willReturn($result);

        $this->beConstructedWith($config,$cache,$jsonDataFetcher,$urlBuilder);

        $this->getRate($baseCur,$compCur)->shouldReturn(3.3);
    }

    function it_returns_zero_if_currency_not_available(Config $config,
                                                     Cache $cache,
                                                     JsonDataFetcher $jsonDataFetcher,
                                                     GoogleUrlBuilder $urlBuilder)
    {
        $result = ['notavailable' => 'notavailable'];
        $baseCur = 'LTL';
        $compCur = 'GBP';
        $duration = 1;

        $config->get('currency::cache_duration')->willReturn($duration);
        $cache->remember($baseCur.$compCur.'Google', $duration, Argument::any())->willReturn($result);

        $this->beConstructedWith($config,$cache,$jsonDataFetcher,$urlBuilder);

        $this->getRate($baseCur,$compCur)->shouldReturn(0);
    }

}
