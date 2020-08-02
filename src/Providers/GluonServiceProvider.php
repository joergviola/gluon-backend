<?php

namespace Gluon\Backend\Providers;

use Gluon\Backend\Console\CreateClient;
use Gluon\Backend\Middleware\HandleError;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class GluonServiceProvider extends ServiceProvider
{

    public function register()
    {
        $config = require __DIR__ . '/../../config/gluon.php';
        $auth = $this->app['config']->get('auth');
        $auth['guards']['gluon'] = $config['auth']['guard']['gluon'];
        $auth['providers']['users'] = $config['auth']['providers']['users'];
        $auth = $this->app['config']->set('auth', $auth);

        $this->app->register(AuthServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->loadRoutesFrom(__DIR__.'/../../routes/index.php');

        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(HandleError::class);

        if ($this->app->runningInConsole()) {

            $this->publishes([
              __DIR__.'/../../config/gluon.php' => config_path('gluon.php'),
            ], 'config');
        
            $this->publishes([
              __DIR__ . '/../../database/migrations/create_gluon.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_gluon.php'),
            ], 'migrations');

            $this->commands([
              CreateClient::class,
            ]);
      
        }
        
    }
}
