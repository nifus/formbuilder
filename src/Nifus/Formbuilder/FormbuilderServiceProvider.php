<?php namespace Nifus\Formbuilder;

use Illuminate\Support\ServiceProvider;

class FormbuilderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['FormBuilder'] = $this->app->share(function($app)
        {
            //return new \NIfus\FormBuilder\FormBuilder;
        });
       // $this->app['config']->package( "nifus/formbuilder", dirname( __FILE__ ) . "/../../../config" );
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
    public function boot()
    {
        $this->package('nifus/formbuilder','formbuilder');
        \View::addNamespace('formbuilder', dirname( __FILE__ ) . "/../..");
         require __DIR__ . '/../../routes.php';
    }

}