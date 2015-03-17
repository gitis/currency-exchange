<?php namespace Gytis\Currency\RateProviders;

class GoogleUrlBuilder{

    public function make($baseCurrency,$compCurrency){
        $url = 'http://rate-exchange.appspot.com/currency?from=' . $baseCurrency . '&to=' . $compCurrency . '&q=1';
        return $url;
    }

}