<?php namespace Gytis\Currency\Commands;

use Gytis\Currency\CurrencyExchange;
use Gytis\Currency\Validators\CommandValidator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetBestCurrencyRateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'currency:rate:best';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Returns the best exchange rate for a given currency pair';

    /**
     * @var CurrencyExchange
     */
    protected $currencyExchange;
    /**
     * @var CommandValidator
     */
    protected $commandValidator;
    /**
     * @var CommandFormatter
     */
    protected $commandFormatter;


    /**
     * @param CurrencyExchange $currencyExchange
     * @param CommandValidator $commandValidator
     * @param CommandFormatter $commandFormatter
     */
    public function __construct(CurrencyExchange $currencyExchange,
                                CommandValidator $commandValidator,
                                CommandFormatter $commandFormatter)
	{
        $this->currencyExchange = $currencyExchange;
        $this->commandValidator = $commandValidator;
        $this->commandFormatter = $commandFormatter;

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
            $this->error($this->commandFormatter->createErrorMessage($this->commandValidator->errors->first()));
            return;
        }

        $bestProvider = $this->currencyExchange->getBestRateProvider($baseCurrency, $compCurrency);

        $this->info($this->commandFormatter->createBestRateMessage($baseCurrency,$compCurrency,$bestProvider->getRate($baseCurrency,$compCurrency),$bestProvider->getName()));
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
