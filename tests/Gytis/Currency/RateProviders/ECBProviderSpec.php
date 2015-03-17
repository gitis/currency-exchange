<?php

namespace tests\Gytis\Currency\RateProviders;

use Gytis\Currency\DataFetchers\XmlDataFetcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class ECBProviderSpec extends ObjectBehavior
{
    function let(Config $config, Cache $cache, XmlDataFetcher $dataFetcher)
    {
        $unparsedResults = ['Cube' =>
                                ['Cube' =>
                                    ['Cube' => [
                                        ['@attributes' => ['currency' => 'USD', 'rate' => '3']],
                                        ['@attributes' => ['currency' => 'GBP', 'rate' => '2']],
                                        ['@attributes' => ['currency' => 'JPY', 'rate' => '0.6']]
                                    ]
                                    ]
                                ]
                            ];

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())->willReturn($unparsedResults);
        $config->get('currency::cache_duration')->willReturn(1);

        $this->beConstructedWith($config,$cache,$dataFetcher);
    }

    function it_is_initializable(Config $config, Cache $cache, XmlDataFetcher $dataFetcher)
    {
        $this->beConstructedWith($config,$cache,$dataFetcher);

        $this->shouldHaveType('Gytis\Currency\RateProviders\ECBProvider');
    }

    function it_calculates_rate_for_EUR_base()
    {
        $this->getRate('EUR','USD')->shouldReturn(3.0);
    }

    function it_calculates_rate_for_other_base(Config $config, Cache $cache, XmlDataFetcher $dataFetcher)
    {
        $this->getRate('GBP', 'USD')->shouldReturn(1.5);
    }

    function it_returns_zero_if_no_currency_found(Config $config, Cache $cache, XmlDataFetcher $dataFetcher)
    {
        $this->getRate('GBP', 'CAD')->shouldReturn(0);
    }

    function it_saves_result_to_cache(Config $config, Cache $cache, XmlDataFetcher $dataFetcher)
    {
        $unparsedResults = ['Cube' =>
            ['Cube' =>
                ['Cube' => [
                    ['@attributes' => ['currency' => 'USD', 'rate' => '3']],
                    ['@attributes' => ['currency' => 'GBP', 'rate' => '2']],
                    ['@attributes' => ['currency' => 'JPY', 'rate' => '0.6']]
                ]
                ]
            ]
        ];

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())->willReturn($unparsedResults);
        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())->shouldBeCalled();
        $config->get('currency::cache_duration')->willReturn(1);

        $this->beConstructedWith($config, $cache, $dataFetcher);

        $this->getRate('GBP', 'CAD');
    }
}
