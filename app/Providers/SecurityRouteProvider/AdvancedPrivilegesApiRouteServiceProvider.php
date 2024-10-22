<?php
namespace App\Providers\SecurityRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\Security\AdvancedPrivileges\ModuleCore\Traits\Routes; // Import your trait

class AdvancedPrivilegesApiRouteServiceProvider extends ServiceProvider
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
