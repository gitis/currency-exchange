<?php

namespace tests\Gytis\Currency\DataFetchers;

use JsonIncrementalParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonDataFetcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gytis\Currency\DataFetchers\JsonDataFetcher');
    }

    function it_throws_exception_on_invalid_json()
    {
        // Cant mock core php functions
        // Haven't still figured how to test it

    }

    function it_fetches_json_data()
    {
        // Cant mock core php functions
        // Haven't still figured how to test it

    }
}
