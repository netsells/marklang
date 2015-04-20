<?php namespace Netsells\Marklang;

use Illuminate\Support\ServiceProvider;

class MarklangServiceProvider extends ServiceProvider {

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
        $this->app->bind('marklang', function() {
            return new Marklang;
        });
    }

}
