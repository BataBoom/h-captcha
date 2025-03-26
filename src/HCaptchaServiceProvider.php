<?php

namespace BataBoom\Captcha;

use Illuminate\Support\ServiceProvider;

class HCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $app = $this->app;

        $this->bootConfig();

        $app['validator']->extend('captcha', function ($attribute, $value) use ($app) {
            return $app['captcha']->verifyResponse($value, $app['request']->getClientIp());
        });

        if ($app->bound('form')) {
            $app['form']->macro('captcha', function ($attributes = []) use ($app) {
                return $app['captcha']->display($attributes, $app->getLocale());
            });
        }
    }

    /**
     * Booting configure.
     */
    protected function bootConfig()
    {
        $path = __DIR__.'/config/hcaptcha.php';
        $reqPath = __DIR__.'/Http/Requests/HCaptchaLoginRequest.php';

        $this->mergeConfigFrom($path, 'hcaptcha');

        if (function_exists('config_path')) {
            $this->publishes([
                $path => config_path('hcaptcha.php'),
                $reqPath => app_path('Http/Requests/Auth/HCaptchaLoginRequest.php'),
            ]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('captcha', function ($app) {
            return new HCaptcha(
                $app['config']['hcaptcha.secret'],
                $app['config']['hcaptcha.sitekey'],
                $app['config']['hcaptcha.options']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['captcha'];
    }
}
