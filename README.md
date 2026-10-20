# Persian Number Input for Filament

> **English** | [فارسی](README-FA.md)

A Persian/Farsi number input field for Filament with RTL digit formatting, thousand separators, and stepper controls.

The field displays numbers in Persian digits (`۰-۹`) while storing clean English digits in the database.

## Features

- Persian/Arabic digit display with English storage
- Configurable thousand separators and grouping direction (left/right)
- Optional stepper buttons
- Digit limit (`maxDigits`) and min/max support
- Works with `integer()` and `numeric()` fields
- Normalizes Persian/Arabic digits and separators before validation and dehydration

## Requirements

- PHP `^8.2`
- Filament `^5.0`

## Installation

```bash
composer require farham-zaker/filament-persian-number-input
```

The service provider is auto-discovered. No extra configuration is required.

## Usage

```php
use FarhamZaker\FilamentPersianNumberInput\PersianNumberInput;

PersianNumberInput::make('price')
    ->label('Price')
    ->required();
```

### Disable the stepper

```php
PersianNumberInput::make('discount_price')
    ->stepper(false);
```

### Custom thousand grouping

```php
PersianNumberInput::make('quantity')
    ->numberGrouping(digits: 3, separator: ',');
```

### Group from the left (e.g. card numbers)

```php
PersianNumberInput::make('card_last4')
    ->maxDigits(16)
    ->numberGrouping(4, '-')
    ->groupingDirection('left');
```

### Limit the number of digits

```php
PersianNumberInput::make('postal_code')
    ->maxDigits(10);
```

## Available Methods

| Method | Description | Default |
|---|---|---|
| `stepper(bool $condition = true)` | Show or hide the stepper buttons | `true` |
| `numberGrouping(int $digits = 3, string\|null $separator = ',')` | Set grouping digits and separator | `3`, `,` |
| `groupingDirection('left' \| 'right')` | Direction of the thousand grouping | `right` |
| `maxDigits(int\|null $digits)` | Maximum number of digits allowed | `null` |

All methods also accept closures where applicable.

## Registering the Plugin (optional)

The field works without registering the plugin, but you may add it to your panel:

```php
use FarhamZaker\FilamentPersianNumberInput\PersianNumberInputPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            PersianNumberInputPlugin::make(),
        ]);
}
```

## Customizing the View

```bash
php artisan vendor:publish --tag=filament-persian-number-input-views
```

The view will be published to `resources/views/vendor/filament-persian-number-input`.

## License

MIT. See [LICENSE](LICENSE).
