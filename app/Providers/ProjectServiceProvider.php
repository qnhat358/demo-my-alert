<?php

namespace App\Providers;

use App\Services\Project\ProjectService;
use App\Services\Project\ProjectServiceInterface;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProjectServiceInterface::class, ProjectService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
