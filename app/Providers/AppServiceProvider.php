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

        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            $storageData = \Illuminate\Support\Facades\Cache::remember('global_storage_metrics', 300, function () {
                $storageBytes = 0;
                try {
                    $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator(base_path(), \FilesystemIterator::SKIP_DOTS)
                    );
                    foreach ($iterator as $file) {
                        $storageBytes += $file->getSize();
                    }
                } catch (\Exception $e) {}

                $storageMB = number_format($storageBytes / 1048576, 1);
                $storagePercent = number_format(($storageMB / 1024) * 100, 1);
                return [
                    'storageMB' => $storageMB,
                    'storagePercent' => $storagePercent
                ];
            });

            $view->with('globalStorageMB', $storageData['storageMB']);
            $view->with('globalStoragePercent', $storageData['storagePercent']);
        });
    }
}
