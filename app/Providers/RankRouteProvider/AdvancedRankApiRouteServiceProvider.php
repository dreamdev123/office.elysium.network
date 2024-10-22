<?php
namespace App\Providers\RankRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Traits\Routes; // Import your trait

class AdvancedRankApiRouteServiceProvider extends ServiceProvider
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
