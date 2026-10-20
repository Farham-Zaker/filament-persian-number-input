<?php

namespace FarhamZaker\FilamentPersianNumberInput;

use Filament\Contracts\Plugin;
use Filament\Panel;

class PersianNumberInputPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'filament-persian-number-input';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
