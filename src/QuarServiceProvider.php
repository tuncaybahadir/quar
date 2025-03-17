<?php

namespace tbQuar;

use Illuminate\Support\ServiceProvider;

class QuarServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->bind('quar', function () {
            return new Generate;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Generate::class];
    }
}
