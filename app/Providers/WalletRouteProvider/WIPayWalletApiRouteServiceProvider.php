<?php
namespace App\Providers\WalletRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\Wallet\IPayWallet\ModuleCore\Traits\Routes; // Import your trait

class WIPayWalletApiRouteServiceProvider extends ServiceProvider
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
