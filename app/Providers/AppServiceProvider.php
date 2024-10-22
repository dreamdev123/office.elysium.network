<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 *  @author Acemero Technologies Pvt Ltd
 *  @link https://www.acemero.com
 *  @see https://www.hybridmlm.io
 *  @version 1.00
 *  @api Laravel 5.4
 */

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use File;
use Illuminate\Support\Str;
/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();

        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $generalDir = app_path('Providers/GeneralRouteProvider');
        $paymentDir = app_path("Providers/PaymentRouteProvider");
        $rankDir = app_path("Providers/RankRouteProvider");
        $reportDir = app_path("Providers/ReportRouteProvider");
        $securityDir = app_path("Providers/SecurityRouteProvider");
        $treeDir = app_path("Providers/TreeRouteProvider");
        $utilityDir = app_path("Providers/UtilityRouteProvider");
        $walletDir = app_path("Providers/WalletRouteProvider");
        // set permission
        chmod($generalDir, 0755);
        chmod($paymentDir, 0755);
        chmod($rankDir, 0755);
        chmod($reportDir, 0755);
        chmod($securityDir, 0755);
        chmod($treeDir, 0755);
        chmod($utilityDir, 0755);
        chmod($walletDir, 0755);
        $this->registerDynamicGeneralRouteProviders();
        $this->registerDynamicPaymentRouteProviders();
        $this->registerDynamicRankRouteProviders();
        $this->registerDynamicReportRouteProviders();
        $this->registerDynamicSecurityRouteProviders();
        $this->registerDynamicUtilityRouteProviders();
        $this->registerDynamicWalletRouteProviders();
        $this->registerDynamicTreeRouteProviders();
    }

    protected function registerDynamicGeneralRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\GeneralRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\GeneralRouteProvider\\' . str_replace(
                ['/', '.php'], 
                ['\\', ''], 
                $file->getRelativePathname()
            );

            // Output class name for debugging

            // Check if the class exists and register it
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }

    protected function registerDynamicPaymentRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\PaymentRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\PaymentRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }

    protected function registerDynamicRankRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\RankRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\RankRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }

    protected function registerDynamicReportRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\ReportRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\ReportRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }
    protected function registerDynamicSecurityRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\SecurityRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\SecurityRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }
    protected function registerDynamicTreeRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\TreeRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);
        
        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\TreeRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }
    protected function registerDynamicUtilityRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\UtilityRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\UtilityRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }
    protected function registerDynamicWalletRouteProviders()
    {
        // Path to your Providers directory
        $providerPath = app_path('Providers\\WalletRouteProvider');

        // Get all PHP files in the Providers directory
        $files = File::allFiles($providerPath);

        // Loop through each file and register it as a provider
        foreach ($files as $file) {
            $class = 'App\\Providers\\WalletRouteProvider\\' . Str::replaceLast('.php', '', $file->getFilename());
            // Register the provider class
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }
}
