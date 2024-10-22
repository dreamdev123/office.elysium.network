<?php
namespace App\Providers\GeneralRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\General\PackageUpgrade\ModuleCore\Traits\Routes; // Import your trait

class PackageUpgradeApiRouteServiceProvider extends ServiceProvider
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
