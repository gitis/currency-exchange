<?php namespace Gytis\Currency\DataFetchers;

class XmlDataFetcher implements DataFetcherInterface{
    /**
     * Fetches XML data from provided URL and returns an array
     *
     * @param string $url
     * @throws \Exception
     * @internal param bool $associative return assoc array if true, object if false
     * @return array
     */
    public function getAssocArray($url)
    {
        $json = json_encode($this->fetch($url));
        $array = json_decode($json, true);

        return $array;
    }

    private function fetch($url)
    {
        $XMLcontent = simplexml_load_file($url);

        if($XMLcontent === FALSE) throw new \Exception('Error parsing data from ' . $url);

        return $XMLcontent;
    }

}