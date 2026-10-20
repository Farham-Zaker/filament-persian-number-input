<?php

namespace FarhamZaker\FilamentPersianNumberInput;

use Illuminate\Support\ServiceProvider;

class PersianNumberInputServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-persian-number-input');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-persian-number-input'),
            ], 'filament-persian-number-input-views');
        }
    }
}
