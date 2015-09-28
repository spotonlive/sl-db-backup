<?php

/**
 * Database backups for Laravel 5.1
 *
 * @license MIT
 * @package SpotOnLive\DbBackup
 */

namespace SpotOnLive\DbBackup\Providers\Services;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use SpotOnLive\DbBackup\Services\BackupService;

class BackupServiceProvider extends ServiceProvider
{
    /**
     * Publish config
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../../config/config.php' => config_path('backup.php'),
        ]);
    }

    /**
     * Register service
     */
    public function register()
    {
        $this->app->bind('SpotOnLive\DbBackup\Services\BackupService', function (Application $app) {
            if (!$backupConfig = config('backup')) {
                $backupConfig = [];
            }

            return new BackupService($backupConfig);
        });

        $this->mergeConfig();
    }

    /**
     * Merge config
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../../config/backup.php',
            'backup'
        );
    }
}
