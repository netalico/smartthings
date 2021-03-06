<?php namespace Netalico\Smartthings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Config;
use \anlutro\cURL\cURL;

class SmartthingsServiceProvider extends ServiceProvider {

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
		$this->package('netalico/smartthings', 'smartthings');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Smartthings', 'Netalico\Smartthings\Facades\Smartthings');
			$loader->alias('Switches', 'Netalico\Smartthings\Facades\Switches');
			$loader->alias('Locks', 'Netalico\Smartthings\Facades\Locks');
		});

		$this->app['smartthings'] = $this->app->share(function($app)
		{
			$smartthings = new Smartthings(new cURL);
			// var_dump(\Config::get('smartthings::clientId'));
			$smartthings->setClientId(\Config::get('smartthings::clientId'));
			$smartthings->setClientSecret(\Config::get('smartthings::clientSecret'));
			$smartthings->setEndpoint(\Config::get('smartthings::endpoint'));

			return $smartthings;
		});

		$this->app['switches'] = $this->app->share(function($app)
		{
			return new Switches(new cURL);
		});

		$this->app['locks'] = $this->app->share(function($app)
		{
			return new Locks(new cURL);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('smartthings');
	}

}
