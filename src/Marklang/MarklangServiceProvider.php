<?php namespace Netsells\Marklang;

use Illuminate\Support\ServiceProvider;

class MarklangServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->package('netsells/marklang');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMarklang();
    }

    public function registerMarklang()
    {
        $this->app->bind('marklang', function($app) {
            return new Marklang($app['translator'], $app['url'], $app['path']);
        });
    }

    public function provides()
    {
        return array('marklang');
    }

}
