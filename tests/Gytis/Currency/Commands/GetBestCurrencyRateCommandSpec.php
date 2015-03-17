<?php

namespace tests\Gytis\Currency\Commands;

use Gytis\Currency\Commands\CommandFormatter;
use Gytis\Currency\CurrencyExchange;
use Gytis\Currency\Validators\CommandValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class GetBestCurrencyRateCommandSpec extends ObjectBehavior
{
    function let(CurrencyExchange $currencyExchange,
                 CommandValidator $commandValidator,
                 CommandFormatter $commandFormatter)
    {
        $this->beConstructedWith($currencyExchange, $commandValidator, $commandFormatter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Gytis\Currency\Commands\GetBestCurrencyRateCommand');
    }

    // Couldn't figure out a way to test this without partial mocking of methods.
    // It would be possible to do using Mockery.

    /*function it_prints_error_message_on_invalid_input(Input $input,
                                                      Output $output,
                                                      CurrencyExchange $currencyExchange,
                                                      CommandValidator $commandValidator,
                                                      CommandFormatter $commandFormatter)
    {
        $cur1 = 'GBP';
        $cur2 = 'JPY';
        $rate1 = 4.4;
        $rate2 = 1.1;
        $provider1 = 'provider1';
        $provider2 = 'provider2';

        $input->getArgument('baseCurrency')->willReturn($cur1);
        $input->getArgument('compCurrency')->willReturn($cur2);
        $currencyExchange->getCurrencyRates($cur1, $cur2)->willReturn(['provider1' => 4.4, 'provider2' => 1.1]);
        $commandValidator->validate(['baseCurrency' => $cur2, 'compCurrency' => $cur1])->willReturn(true);

        $currencyExchange->getCurrencyRates($cur1, $cur2)->shouldBeCalled();
        $commandFormatter->createRateInfoMessage($cur1,$cur2,$rate1,$provider1)->shouldBeCalled();
        $commandFormatter->createRateInfoMessage($cur1,$cur2,$rate2,$provider2)->shouldBeCalled();

        $this->fire();

    }*/
}
