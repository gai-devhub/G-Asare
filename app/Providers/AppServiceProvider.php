<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Document;
use App\Models\Education;
use App\Models\Experience;
use App\Observers\PortfolioObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        Project::observe(PortfolioObserver::class);
        Skill::observe(PortfolioObserver::class);
        Document::observe(PortfolioObserver::class);
        Education::observe(PortfolioObserver::class);
        Experience::observe(PortfolioObserver::class);
    }
}
