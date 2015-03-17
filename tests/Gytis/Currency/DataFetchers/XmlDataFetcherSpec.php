<?php

namespace tests\Gytis\Currency\DataFetchers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XmlDataFetcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gytis\Currency\DataFetchers\XmlDataFetcher');
    }

    function it_throws_an_exception_on_parse_error()
    {
        // Cant mock core php functions
        // Haven't still figured how to test it
    }

    function it_fetches_xml_data()
    {
        // Cant mock core php functions
        // Haven't still figured how to test it

    }

}
