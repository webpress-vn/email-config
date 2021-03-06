<?php

namespace VCComponent\Laravel\Mail\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Mail\Facades\MailConfig;
use VCComponent\Laravel\Mail\Repositories\MailRepository;
use VCComponent\Laravel\Mail\Repositories\MailRepositoryEloquent;
use VCComponent\Laravel\Mail\Services\MailConfigService;
use Exception;

class MailConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MailRepository::class, MailRepositoryEloquent::class);
        $this->app->bind('mailConfig', MailConfigService::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'mail');

        try {
            $this->configMail();
        } catch (Exception $e) {}
    }

    private function configMail()
    {
        $configs = MailConfig::getConfigFromDB();
        MailConfig::setConfig($configs);
    }
}
