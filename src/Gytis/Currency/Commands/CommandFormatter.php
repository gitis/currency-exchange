<?php namespace Gytis\Currency\Commands;

class CommandFormatter{
    /**
     * Creates errors message from validator error
     *
     * @param string $error
     * @return string
     */
    public function createErrorMessage($error){
        return 'Invalid input. ' . $error;
    }

    /**
     * Creates console line output for best rate
     *
     * @param string $baseCurrency
     * @param string $compCurrency
     * @param double $rate
     * @param string $providerName
     * @return string
     */
    public function createBestRateMessage($baseCurrency, $compCurrency, $rate, $providerName){
        return sprintf('Best rate for %s to %s = %.2f at %s', $baseCurrency, $compCurrency, $rate, $providerName);
    }

    /**
     * Create exchange rate report info message for console
     *
     * @param string $baseCurrency
     * @param string $compCurrency
     * @param double $rate
     * @param string $providerName
     * @return string
     */
    public function createRateInfoMessage($baseCurrency, $compCurrency, $rate, $providerName){
        return sprintf('%s to %s = %.2f at %s', $baseCurrency, $compCurrency, $rate, $providerName);
    }
}