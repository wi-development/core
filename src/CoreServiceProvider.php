<?php

namespace WI\Core;


use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class CoreServiceProvider extends ServiceProvider
{


	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	#protected $defer = true;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{

		if (is_dir(base_path() . '/resources/views/admin/core')) {
			//load from resource
			$this->loadViewsFrom(base_path() . '/resources/views/admin/core', 'core');
		} else {
			//load from package
			$this->loadViewsFrom(__DIR__.'/views', 'core');
		}



		if (!$this->app->routesAreCached()) {
			$this->setupRoutes($this->app->router);
		}

		config([
			'config/wi/core.php',
		]);

		$this->publishes([
			__DIR__.'/views' => base_path('resources/views/admin/core'),
			__DIR__.'/config/core.php' => config_path('wi/core.php')
		],'core');
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function setupRoutes(Router $router)
	{
		$router->group([
			//'namespace' => 'WI\Core',
			'namespace' => 'WI\Core',	// Controllers Within The "WI\Core" Namespace
			'as' => 'admin::',		// Route named "admin::
			//'prefix' => 'backStage',	// Matches The "/admin" URL
			'prefix' => config('wi.dashboard.admin_prefix'),
			'middleware' => ['web','auth']	// Use Auth Middleware
		],
			function($router)
			{
				require __DIR__.'/routes.php';
			}
		);

	}

	/**
	 * Register the application services.
	 * https://laracasts.com/discuss/channels/general-discussion/how-to-move-my-controllers-into-a-seperate-package-folder
	 * @return void
	 */
	public function register()
	{
		#dd('asdf');
		#include __DIR__.'/routes.php';
		//$this->app->make('WI\Core\CoreController');

		//$this->app->register(Vendor\Package\Providers\RouteServiceProvider::class);

		$this->app->bind(
			'WI\Core\Repositories\Reference\ReferenceRepositoryInterface',
			'WI\Core\Repositories\Reference\DbReferenceRepository'
		);
		/*
		$this->app->bind(
			'Repositories\Reference\DbReferenceRepository',
			function () {
				$repository = new EloquentPageRepository(new Page());
				return $repository;
				if (! Config::get('app.cache')) {
					return $repository;
				}

				return new CachePageDecorator($repository);

			}
		);
*/


	}
}
