<?php

namespace tests\Gytis\Currency\RateProviders;

use Gytis\Currency\DataFetchers\JsonDataFetcher;
use Gytis\Currency\RateProviders\OpenExchangeRateUrlBuilder;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OpenExchangeRateProviderSpec extends ObjectBehavior
{
    function let(Config $config, Cache $cache, JsonDataFetcher $dataFetcher, OpenExchangeRateUrlBuilder $urlBuilder)
    {
        $unparsedResults = ['rates' => ['EUR' => 1.5,
                                        'GBP' => 3.0,
                                        'JPY' => 2.0]];

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())->willReturn($unparsedResults);
        $config->get('currency::cache_duration')->willReturn(1);
        $config->get('currency::oer_app_id')->willReturn('appID');
        $urlBuilder->make('appID')->willReturn('url');

        $this->beConstructedWith($config,$cache,$dataFetcher,$urlBuilder);
    }

    function it_is_initializable(Config $config, Cache $cache, JsonDataFetcher $dataFetcher, OpenExchangeRateUrlBuilder $urlBuilder)
    {
        $this->beConstructedWith($config, $cache, $dataFetcher, $urlBuilder);
        $this->shouldHaveType('Gytis\Currency\RateProviders\OpenExchangeRateProvider');
    }

    function it_calculates_rate_for_USD_base()
    {
        $this->getRate('USD','GBP')->shouldReturn(3.0);
    }

    function it_calculates_rate_for_other_base()
    {
        $this->getRate('JPY', 'GBP')->shouldReturn(1.5);
    }

    function it_returns_zero_if_no_currency_found()
    {
        $this->getRate('LTL', 'EUR');
    }

    function it_saves_result_to_cache(Config $config, Cache $cache, JsonDataFetcher $dataFetcher, OpenExchangeRateUrlBuilder $urlBuilder)
    {
        $unparsedResults = ['rates' => ['EUR' => 1.5,
                                        'GBP' => 3.0,
                                        'JPY' => 2.0]];

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())->willReturn($unparsedResults);
        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())->shouldBeCalled();

        $config->get('currency::cache_duration')->willReturn(1);
        $config->get('currency::oer_app_id')->willReturn('appID');
        $urlBuilder->make('appID')->willReturn('url');

        $this->beConstructedWith($config,$cache,$dataFetcher,$urlBuilder);

        $this->getRate('USD','GBP');
    }
}
