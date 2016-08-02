<?php
namespace Padosoft\WorkbenchVersion;

use Illuminate\Support\ServiceProvider;


class WorkbenchVersionServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['command.workbench:version'] = $this->app->share(
            function ($app) {
                return new WorkbenchVersion();
            }
        );
        $this->commands('command.workbench:version');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.workbench:version'];
    }
}