<?php
/**
 * Description of ServiceProvider.php
 * @copyright Copyright (c) MISTER.AM, LLC
 * @author    Liuba Kalyta <kalyta@dotsplatform.com>
 */

namespace Dotsplatform\Verification;

use Illuminate\Support\ServiceProvider;

class VerificationApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/verification-api-sdk.php',
            'verification-server'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/verification-api-sdk.php' => config_path('verification-api-sdk'),
        ], 'config');
    }
}
