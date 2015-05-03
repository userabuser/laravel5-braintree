<?php namespace Oureastudios\Laravel;

use Illuminate\Support\ServiceProvider;

use Braintree_Configuration;
use Braintree_ClientToken;

use Blade;

class BraintreeServiceProvider extends ServiceProvider {

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
		
		$this->publishes([
		    __DIR__.'/../../config/braintree.php' => config_path('oureastudios.braintree.php'),
		]);

		Braintree_Configuration::environment(
			$this->app['config']->get('oureastudios.braintree.environment')
		);
		
		Braintree_Configuration::merchantId(
			$this->app['config']->get('oureastudios.braintree.merchantId')
		);

		Braintree_Configuration::publicKey(
			$this->app['config']->get('oureastudios.braintree.publicKey')
		);

		Braintree_Configuration::privateKey(
			$this->app['config']->get('oureastudios.braintree.privateKey')
		);

		$encryptionKey = $this->app['config']->get('oureastudios.braintree.clientSideEncryptionKey');

		Blade::extend(function($view, $compiler) use($encryptionKey)
        {

        	$matcher = "/(?<!\w)(\s*)@braintreeClientSideEncryptionKey/";

			return preg_replace($matcher, $encryptionKey, $view);
        });

        Blade::extend(function($view, $compiler)
        {

        	$matcher = "/(?<!\w)(\s*)@braintreeClientToken/";

			return preg_replace($matcher, Braintree_ClientToken::generate(), $view);
        });
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
