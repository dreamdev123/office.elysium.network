<?php
namespace App\Providers\PaymentRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\Payment\ZotaPay\ModuleCore\Traits\Routes; // Import your trait

class ZotaPayApiRouteServiceProvider extends ServiceProvider
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
