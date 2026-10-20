# کامپوننت ورود عدد فارسی برای فیلامنت

[English](README.md) | **فارسی**

یک فیلد ورود عدد فارسی برای فیلامنت با نمایش ارقام راست‌به‌چپ، جداکننده هزارگان و دکمه‌های stepper.

این فیلد اعداد را با ارقام فارسی (`۰-۹`) نمایش می‌دهد و مقدار تمیز و انگلیسی را در دیتابیس ذخیره می‌کند.

## امکانات

- نمایش ارقام فارسی/عربی با ذخیره‌سازی به زبان انگلیسی
- جداکننده هزارگان قابل پیکربندی و جهت گروه‌بندی (چپ/راست)
- دکمه‌های stepper اختیاری
- محدودیت تعداد رقم (`maxDigits`) و پشتیبانی از min/max
- سازگار با فیلدهای `integer()` و `numeric()`
- نرمال‌سازی ارقام فارسی/عربی و جداکننده‌ها قبل از validation و ذخیره نهایی

## پیش‌نیازها

- PHP نسخه `^8.2`
- فیلامنت نسخه `^5.0`

## نصب

```bash
composer require farham-zaker/filament-persian-number-input
```

ServiceProvider به صورت خودکار شناسایی (auto-discover) می‌شود و به هیچ پیکربندی اضافه‌ای نیاز ندارید.

## استفاده

```php
use FarhamZaker\FilamentPersianNumberInput\PersianNumberInput;

PersianNumberInput::make('price')
    ->label('قیمت')
    ->required();
```

### غیرفعال کردن stepper

```php
PersianNumberInput::make('discount_price')
    ->stepper(false);
```

### گروه‌بندی هزارگان سفارشی

```php
PersianNumberInput::make('quantity')
    ->numberGrouping(digits: 3, separator: ',');
```

### گروه‌بندی از سمت چپ (مثلاً شماره کارت)

```php
PersianNumberInput::make('card_last4')
    ->maxDigits(16)
    ->numberGrouping(4, '-')
    ->groupingDirection('left');
```

### محدود کردن تعداد ارقام

```php
PersianNumberInput::make('postal_code')
    ->maxDigits(10);
```

## متدهای قابل استفاده

| مетод | توضیحات | پیش‌فرض |
|---|---|---|
| `stepper(bool $condition = true)` | نمایش یا مخفی کردن دکمه‌های stepper | `true` |
| `numberGrouping(int $digits = 3, string\|null $separator = ',')` | تعیین تعداد رقم و جداکننده گروه‌بندی | `3`, `,` |
| `groupingDirection('left' \| 'right')` | جهت گروه‌بندی هزارگان | `right` |
| `maxDigits(int\|null $digits)` | حداکثر تعداد مجاز ارقام | `null` |

تمام متد‌ها در صورت نیاز Closure هم می‌پذیرند.

## ثبت پلاگین (اختیاری)

فیلد بدون ثبت پلاگین هم کار می‌کند، اما می‌توانید آن را به پنل اضافه کنید:

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

## سفارشی‌سازی ویو

```bash
php artisan vendor:publish --tag=filament-persian-number-input-views
```

ویو در مسیر `resources/views/vendor/filament-persian-number-input` منتشر می‌شود.

## لایسنس

MIT — فایل [LICENSE](LICENSE) را ببینید.
