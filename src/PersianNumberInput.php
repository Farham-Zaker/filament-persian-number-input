<?php

namespace FarhamZaker\FilamentPersianNumberInput;

use Closure;
use Filament\Forms\Components\TextInput;

class PersianNumberInput extends TextInput
{
    protected string $view = 'filament-persian-number-input::persian-number-input';

    protected bool|Closure $hasStepper = true;

    protected int $numberGroupingDigits = 3;

    protected string|Closure|null $numberGroupingSeparator = null;

    protected string|Closure $groupingDirection = 'right';

    protected int|Closure|null $maxDigits = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->mutateStateForValidationUsing(fn ($state) => $this->normalizeToEnglish($state))
            ->dehydrateStateUsing(fn ($state) => $this->normalizeToEnglish($state));
    }

    public function stepper(bool|Closure $condition = true): static
    {
        $this->hasStepper = $condition;

        return $this;
    }

    public function hasStepper(): bool
    {
        return (bool) $this->evaluate($this->hasStepper);
    }

    public function numberGrouping(int $digits = 3, string|Closure|null $separator = ','): static
    {
        $this->numberGroupingDigits = $digits;
        $this->numberGroupingSeparator = $separator;

        return $this;
    }

    public function getNumberGroupingDigits(): int
    {
        return $this->numberGroupingDigits;
    }

    public function getNumberGroupingSeparator(): ?string
    {
        return $this->evaluate($this->numberGroupingSeparator);
    }

    public function groupingDirection(string|Closure $direction = 'right'): static
    {
        $this->groupingDirection = $direction;

        return $this;
    }

    public function getGroupingDirection(): string
    {
        return $this->evaluate($this->groupingDirection) === 'left' ? 'left' : 'right';
    }

    public function maxDigits(int|Closure|null $digits): static
    {
        $this->maxDigits = $digits;

        return $this;
    }

    public function getMaxDigits(): ?int
    {
        $digits = $this->evaluate($this->maxDigits);

        return $digits === null ? null : (int) $digits;
    }

    public function getType(): string
    {
        return 'text';
    }

    public function normalizeToEnglish(string|int|float|null $state): string|int|float|null
    {
        if (blank($state)) {
            return $state;
        }

        $replacements = [',', '٬', '،', ' '];

        $separator = $this->getNumberGroupingSeparator();

        if (filled($separator)) {
            $replacements[] = $separator;
        }

        return str_replace($replacements, '', NumberUtils::toEnglish($state));
    }
}
