<?php

namespace tests\Gytis\Currency\Commands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandFormatterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gytis\Currency\Commands\CommandFormatter');
    }

    function it_should_format_proper_error_message(){
        $this->createErrorMessage('test error')->shouldReturn('Invalid input. test error');
    }

    function it_should_format_proper_best_rate_message(){
        $result = 'Best rate for USD to GBP = 3.43 at ECB';
        $baseCurrency = 'USD';
        $compCurrency = 'GBP';
        $rate = 3.43;
        $provider = 'ECB';

        $this->createBestRateMessage($baseCurrency,$compCurrency,$rate,$provider)->shouldReturn($result);
    }

    function it_should_format_proper_rate_line(){
        $result = 'USD to GBP = 3.43 at ECB';
        $baseCurrency = 'USD';
        $compCurrency = 'GBP';
        $rate = 3.43;
        $provider = 'ECB';

        $this->createRateInfoMessage($baseCurrency,$compCurrency,$rate,$provider)->shouldReturn($result);
    }
}
