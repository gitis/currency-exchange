<?php namespace Gytis\Currency;

use Gytis\Currency\Commands\GetBestCurrencyRateCommand;
use Gytis\Currency\Commands\GetCurrencyRatesCommand;
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
            return new CurrencyExchange(\App::make('RateProviderManager'));
        });

        $this->app->bindShared('CommandValidator', function() {
            return new CommandValidator();
        });

        $this->app->bindShared('currency:rates', function(){
            return new GetCurrencyRatesCommand(\App::make('CurrencyExchange'), \App::make('CommandValidator'));
        });

        $this->app->bindShared('currency:rate:best', function(){
            return new GetBestCurrencyRateCommand(\App::make('CurrencyExchange'), \App::make('CommandValidator'));
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
