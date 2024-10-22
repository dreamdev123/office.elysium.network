<?php
namespace App\Providers\ReportRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\Report\TopEarnersReport\ModuleCore\Traits\Routes; // Import your trait

class TopEarnersReportApiRouteServiceProvider extends ServiceProvider
{
    use Routes;

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Call the trait method to set the SafeCharge API routes
        $this->setRoutes();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
