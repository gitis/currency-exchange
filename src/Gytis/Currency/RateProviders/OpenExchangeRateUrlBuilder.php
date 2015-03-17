<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/17/15
 * Time: 2:22 AM
 */

namespace Gytis\Currency\RateProviders;


class OpenExchangeRateUrlBuilder {
    public function make($appId){
        return 'http://openexchangerates.org/api/latest.json?app_id=' . $appId;
    }
} 