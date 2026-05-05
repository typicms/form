<?php

namespace TypiCMS\Form;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Form\ErrorStore\IlluminateErrorStore;
use TypiCMS\Form\OldInput\IlluminateOldInputProvider;

class FormServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->registerErrorStore();
        $this->registerOldInput();
        $this->registerFormBuilder();
    }

    protected function registerErrorStore(): void
    {
        $this->app->singleton('typicms.form.errorstore', fn (Application $app): IlluminateErrorStore => new IlluminateErrorStore($app['session.store']));
    }

    protected function registerOldInput(): void
    {
        $this->app->singleton('typicms.form.oldinput', fn (Application $app): IlluminateOldInputProvider => new IlluminateOldInputProvider($app['session.store']));
    }

    protected function registerFormBuilder(): void
    {
        $this->app->singleton('typicms.form', function (Application $app): FormBuilder {
            $formBuilder = new FormBuilder;
            $formBuilder->setErrorStore($app['typicms.form.errorstore']);
            $formBuilder->setOldInputProvider($app['typicms.form.oldinput']);
            $formBuilder->setToken($app['session.store']->token());

            return $formBuilder;
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['typicms.form'];
    }
}
