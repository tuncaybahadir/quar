<?php

namespace tbQuar;

use Illuminate\Support\ServiceProvider;

class QuarServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
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
    public function provides(): array
    {
        return [Generate::class];
    }
}
