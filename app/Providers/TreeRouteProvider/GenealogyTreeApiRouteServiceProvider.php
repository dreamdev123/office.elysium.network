<?php
namespace App\Providers\TreeRouteProvider;

use Illuminate\Support\ServiceProvider;
use App\Components\Modules\Tree\GenealogyTree\Traits\Routes; // Import your trait

class GenealogyTreeApiRouteServiceProvider extends ServiceProvider
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
