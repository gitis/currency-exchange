<?php namespace Gytis\Currency\DataFetchers;

interface DataFetcherInterface {

    /**
     * Fetches data from provided URL and returns an array
     *
     * @param string $url
     * @internal param bool $associative return assoc array if true, object if false
     * @return array
     */
    public function getAssocArray($url);
}