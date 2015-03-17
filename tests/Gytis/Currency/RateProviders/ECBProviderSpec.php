<?php

namespace tests\Gytis\Currency\RateProviders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ECBProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gytis\Currency\RateProviders\ECBProvider');
    }
}
