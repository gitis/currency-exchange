<?php namespace Gytis\Currency\Commands;

use Gytis\Currency\CurrencyExchange;
use Gytis\Currency\Validators\CommandValidator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetCurrencyRatesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'currency:rates';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Returns an array of currency rates from various providers for a given currency pair';

    /**
     * Injected CurrencyExchange
     *
     * @var CurrencyExchange
     */
    protected $currencyExchange;
    /**
     * Injected CommandValidator
     *
     * @var CommandValidator
     */
    protected $commandValidator;

    /**
     * Create a new command instance.
     * @param CurrencyExchange $currencyExchange
     * @param CommandValidator $commandValidator
     */
	public function __construct(CurrencyExchange $currencyExchange, CommandValidator $commandValidator)
	{
        $this->currencyExchange = $currencyExchange;
        $this->commandValidator = $commandValidator;
        parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->info('Getting data from exchanges...');

        $baseCurrency = $this->argument('baseCurrency');
        $compCurrency = $this->argument('compCurrency');

        if(!$this->commandValidator->validate(compact('baseCurrency','compCurrency')))
        {
            $this->error('Invalid input. ' . (string)$this->commandValidator->errors->first());
            return;
        }

        $rates = $this->currencyExchange->getCurrencyRates($baseCurrency, $compCurrency);

        if(!empty($rates)){
            foreach($rates as $exchange => $rate){
                $this->info($baseCurrency . ' to ' . $compCurrency . ' = ' . $rate . ' @ ' . @$exchange);
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
        return array(
            array('baseCurrency', InputArgument::REQUIRED, 'Base currency.'),
            array('compCurrency', InputArgument::REQUIRED, 'Currency you want to compare to.')
        );
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
