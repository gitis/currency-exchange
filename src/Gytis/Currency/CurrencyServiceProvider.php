<?php namespace Gytis\Currency;

use Gytis\Currency\Commands\CommandFormatter;
use Gytis\Currency\Commands\GetBestCurrencyRateCommand;
use Gytis\Currency\Commands\GetCurrencyRatesCommand;
use Gytis\Currency\DataFetchers\JsonDataFetcher;
use Gytis\Currency\DataFetchers\XmlDataFetcher;
use Gytis\Currency\RateProviders\ECBProvider;
use Gytis\Currency\RateProviders\GoogleProvider;
use Gytis\Currency\RateProviders\GoogleUrlBuilder;
use Gytis\Currency\RateProviders\OpenExchangeRateProvider;
use Gytis\Currency\RateProviders\OpenExchangeRateUrlBuilder;
use Gytis\Currency\Validators\CommandValidator;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('gytis/currency');

        $loader = AliasLoader::getInstance();
        $loader->alias('CurrencyProvider', 'Gytis\Currency\Facades\CurrencyProvider');

        $this->commands([
            'currency:rates',
            'currency:rate:best'
        ]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('RateProviderManager', function($app){
           return new RateProviderManager($app);
        });

        $this->app->bindShared('CurrencyExchange', function(){
            return new CurrencyExchange(\App::make('RateProviderManager'), \App::make('Illuminate\Config\Repository'));
        });

        $this->app->bindShared('CommandValidator', function() {
            return new CommandValidator();
        });

        $this->app->bindShared('CommandFormatter', function() {
            return new CommandFormatter();
        });

        $this->app->bindShared('GoogleProvider', function(){
            return new GoogleProvider(\App::make('Illuminate\Config\Repository'), \App::make('Illuminate\Cache\Repository'), new JsonDataFetcher(), new GoogleUrlBuilder());
        });

        $this->app->bindShared('OpenExchangeRateProvider', function(){
            return new OpenExchangeRateProvider(\App::make('Illuminate\Config\Repository'), \App::make('Illuminate\Cache\Repository'), new JsonDataFetcher(), new OpenExchangeRateUrlBuilder());
        });

        $this->app->bindShared('ECBProvider', function(){
            return new ECBProvider(\App::make('Illuminate\Config\Repository'), \App::make('Illuminate\Cache\Repository'), new XmlDataFetcher());
        });

        $this->app->bindShared('currency:rates', function(){
            return new GetCurrencyRatesCommand(\App::make('CurrencyExchange'), \App::make('CommandValidator'), \App::make('CommandFormatter'));
        });

        $this->app->bindShared('currency:rate:best', function(){
            return new GetBestCurrencyRateCommand(\App::make('CurrencyExchange'), \App::make('CommandValidator'), \App::make('CommandFormatter'));
        });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('currency');
	}

}
