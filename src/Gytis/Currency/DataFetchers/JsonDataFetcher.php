<?php namespace Gytis\Currency\DataFetchers;

class JsonDataFetcher implements DataFetcherInterface{

    /**
     * Returns associative array from JSON file located at provided URL
     *
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function getAssocArray($url)
    {
        return $this->fetch($url);
    }

    /**
     * Fetches json data from provided URL
     *
     * @param string $url
     * @param bool $assoc return assoc array if true, object if false
     * @throws \Exception
     * @return array
     */
    private function fetch($url, $assoc = true)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $jsonResult = curl_exec($ch);
        curl_close($ch);

        $decodedResult = json_decode($jsonResult,$assoc);
        if($decodedResult === null) throw new \Exception('Received invalid JSON from ' . $url);

        return $decodedResult;
    }
}