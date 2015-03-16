<?php

namespace tests\Gytis\Currency;

use Gytis\Currency\RateProviderManager;
use Gytis\Currency\RateProviders\RateProviderInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Config\Repository as Config;

class CurrencyExchangeSpec extends ObjectBehavior
{
    function it_is_initializable(RateProviderManager $rateProviderManager,
                                 Config $config,
                                 RateProviderInterface $provider1,
                                 RateProviderInterface $provider2)
    {

        $rateProviderManager->driver('prov1')->willReturn($provider1);
        $rateProviderManager->driver('prov2')->willReturn($provider2);
        $config->get('currency::providers')->willReturn(array('prov1','prov2'));

        $rateProviderManager->driver('prov1')->shouldBeCalled();
        $rateProviderManager->driver('prov2')->shouldBeCalled();
        $this->beConstructedWith($rateProviderManager, $config);

        $this->shouldHaveType('Gytis\Currency\CurrencyExchange');
    }

    function it_throws_an_exception_if_there_is_no_providers(RateProviderManager $rateProviderManager,
                                                             Config $config)
    {
        $config->get('currency::providers')->willReturn(null);
        $this->shouldThrow(new \RuntimeException('No providers available in the configuration file'));

        $this->beConstructedWith($rateProviderManager, $config);
    }

    function it_returns_best_rate_provider(RateProviderManager $rateProviderManager,
                                           Config $config,
                                           RateProviderInterface $provider1,
                                           RateProviderInterface $provider2,
                                           RateProviderInterface $provider3)
    {
        $currency1 = 'GBP';
        $currency2 = 'JPY';

        $dataArray = array(
            'provider1' => 3.41,
            'provider2' => 1.1,
            'provider3' => 2.9
        );

        foreach($dataArray as $providerName => $rate){
            $$providerName->getRate($currency1,$currency2)->willReturn($rate);
            $$providerName->getName()->willReturn($providerName);
        }

        $rateProviderManager->driver('prov1')->willReturn($provider1);
        $rateProviderManager->driver('prov2')->willReturn($provider2);
        $rateProviderManager->driver('prov3')->willReturn($provider3);
        $config->get('currency::providers')->willReturn(array('prov1','prov2','prov3'));


        $rateProviderManager->driver('prov1')->shouldBeCalled();
        $rateProviderManager->driver('prov2')->shouldBeCalled();
        $rateProviderManager->driver('prov3')->shouldBeCalled();
        $this->beConstructedWith($rateProviderManager, $config);

        $this->getBestRateProvider($currency1, $currency2)->shouldReturn($provider1);
    }

    function it_returns_best_rate_provider_from_one_provider(RateProviderManager $rateProviderManager,
                                                             Config $config,
                                                             RateProviderInterface $provider1)
    {
        $currency1 = 'GBP';
        $currency2 = 'JPY';

        $dataArray = array(
            'provider1' => 3.41,
        );

        foreach($dataArray as $providerName => $rate){
            $$providerName->getRate($currency1,$currency2)->willReturn($rate);
            $$providerName->getName()->willReturn($providerName);
        }

        $rateProviderManager->driver('prov1')->willReturn($provider1);
        $config->get('currency::providers')->willReturn(array('prov1'));


        $rateProviderManager->driver('prov1')->shouldBeCalled();
        $this->beConstructedWith($rateProviderManager, $config);

        $this->getBestRateProvider($currency1, $currency2)->shouldReturn($provider1);
    }

    function it_returns_all_currency_rates_with_three_providers(RateProviderManager $rateProviderManager,
                                           Config $config,
                                           RateProviderInterface $provider1,
                                           RateProviderInterface $provider2,
                                           RateProviderInterface $provider3)
    {
        $currency1 = 'GBP';
        $currency2 = 'JPY';

        $dataArray = array(
            'provider1' => 3.41,
            'provider2' => 1.1,
            'provider3' => 2.9
        );

        foreach($dataArray as $providerName => $rate){
            $$providerName->getRate($currency1,$currency2)->willReturn($rate);
            $$providerName->getName()->willReturn($providerName);
        }

        $rateProviderManager->driver('prov1')->willReturn($provider1);
        $rateProviderManager->driver('prov2')->willReturn($provider2);
        $rateProviderManager->driver('prov3')->willReturn($provider3);
        $config->get('currency::providers')->willReturn(array('prov1','prov2','prov3'));


        $rateProviderManager->driver('prov1')->shouldBeCalled();
        $rateProviderManager->driver('prov2')->shouldBeCalled();
        $rateProviderManager->driver('prov3')->shouldBeCalled();

        // Initialize mocked class
        $this->beConstructedWith($rateProviderManager, $config);

        $this->getCurrencyRates($currency1, $currency2)->shouldReturn($dataArray);
    }

    function it_returns_currency_rates_with_one_provider(RateProviderManager $rateProviderManager,
                                                         Config $config,
                                                         RateProviderInterface $provider1)
    {
        $currency1 = 'GBP';
        $currency2 = 'JPY';

        $dataArray = array(
            'provider1' => 3.41
        );

        foreach($dataArray as $providerName => $rate){
            $$providerName->getRate($currency1,$currency2)->willReturn($rate);
            $$providerName->getName()->willReturn($providerName);
        }

        $rateProviderManager->driver('prov1')->willReturn($provider1);
        $config->get('currency::providers')->willReturn(array('prov1'));


        $rateProviderManager->driver('prov1')->shouldBeCalled();

        // Initialize mocked class
        $this->beConstructedWith($rateProviderManager, $config);

        $this->getCurrencyRates($currency1, $currency2)->shouldReturn($dataArray);
    }
}
