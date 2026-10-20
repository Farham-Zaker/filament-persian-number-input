@php
    $step = is_numeric($field->getStep()) ? (float) $field->getStep() : 1;
    $min = is_numeric($field->getMinValue()) ? (float) $field->getMinValue() : null;
    $max = is_numeric($field->getMaxValue()) ? (float) $field->getMaxValue() : null;
    $showStepper = $field->hasStepper() && (! $isDisabled()) && (! $isReadOnly());
@endphp

@once
    <style>
        .fi-persian-number-stepper-button {
            display: flex;
            flex: 1 1 0%;
            align-items: center;
            justify-content: center;
            min-height: 0.875rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: inherit;
            opacity: 0.5;
            cursor: pointer;
            transition: opacity 75ms;
        }

        .fi-persian-number-stepper-button:hover {
            opacity: 1;
        }

        .fi-persian-number-stepper-button:focus {
            outline: none;
        }

        .fi-persian-number-affix {
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .fi-persian-number-affix-start {
            padding-left: 0.75rem;
            padding-right: 0.5rem;
        }

        .fi-persian-number-affix-end {
            padding-left: 0.5rem;
            padding-right: 0.75rem;
        }
    </style>
@endonce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
            display: '',
            min: {{ \Illuminate\Support\Js::from($min) }},
            max: {{ \Illuminate\Support\Js::from($max) }},
            step: {{ \Illuminate\Support\Js::from($step) }},
            separator: {{ \Illuminate\Support\Js::from($field->getNumberGroupingSeparator()) }},
            groupingDigits: {{ \Illuminate\Support\Js::from($field->getNumberGroupingDigits()) }},
            groupingDirection: {{ \Illuminate\Support\Js::from($field->getGroupingDirection()) }},
            maxDigits: {{ \Illuminate\Support\Js::from($field->getMaxDigits()) }},
            integer: {{ \Illuminate\Support\Js::from($field->isInteger()) }},
            init() {
                this.display = this.toPersian(this.state);

                this.$watch('state', (value) => {
                    this.display = this.toPersian(value);
                });
            },
            toEnglish(value) {
                if (value === null || value === undefined) {
                    return '';
                }

                const string = String(value)
                    .replace(/[\u06F0-\u06F9]/g, (digit) => String(digit.charCodeAt(0) - 0x06F0))
                    .replace(/[\u0660-\u0669]/g, (digit) => String(digit.charCodeAt(0) - 0x0660));

                const negative = string.trimStart().startsWith('-');
                const pattern = this.integer ? /[^\d]/g : /[^\d.]/g;
                const normalized = string.replace(pattern, '');

                return negative && normalized !== '' ? `-${normalized}` : normalized;
            },
            toPersian(value) {
                if (value === null || value === undefined || value === '') {
                    return '';
                }

                const [integer, decimal] = String(value).split('.');
                const grouped = this.separator
                    ? (this.groupingDirection === 'left'
                        ? integer.replace(new RegExp('(\\d{' + this.groupingDigits + '})(?=\\d)', 'g'), (match) => match + this.separator)
                        : integer.replace(new RegExp('\\B(?=(\\d{' + this.groupingDigits + '})+(?!\\d))', 'g'), () => this.separator))
                    : integer;
                const formatted = decimal !== undefined ? `${grouped}.${decimal}` : grouped;

                return formatted.replace(/\d/g, (digit) => String.fromCharCode(0x06F0 + Number(digit)));
            },
            limitDigits(value) {
                if (this.maxDigits === null || value === null || value === undefined) {
                    return value;
                }

                let digits = 0;

                return String(value)
                    .split('')
                    .filter((character) => {
                        if (!/\d/.test(character)) {
                            return true;
                        }

                        digits++;

                        return digits <= this.maxDigits;
                    })
                    .join('');
            },
            update(event) {
                const normalized = this.limitDigits(this.toEnglish(event.target.value));

                this.state = normalized === '' ? null : normalized;
                this.display = this.toPersian(this.state);
                event.target.value = this.display;
            },
            normalize() {
                const value = Number(this.state);

                if (this.state === null || this.state === '' || isNaN(value)) {
                    this.state = null;
                    this.display = '';

                    return;
                }

                if (this.min !== null && value < this.min) {
                    this.state = String(this.min);
                }

                if (this.max !== null && value > this.max) {
                    this.state = String(this.max);
                }

                this.display = this.toPersian(this.state);
            },
            increment() {
                this.changeBy(this.step);
            },
            decrement() {
                this.changeBy(-this.step);
            },
            changeBy(amount) {
                const current = this.state === null || this.state === '' || isNaN(Number(this.state))
                    ? (this.min ?? 0)
                    : Number(this.state);

                let next = current + amount;

                if (this.min !== null && next < this.min) {
                    next = this.min;
                }

                if (this.max !== null && next > this.max) {
                    next = this.max;
                }

                this.state = String(next);
                this.display = this.toPersian(this.state);
            },
        }"
        dir="ltr"
        {{ $getExtraAttributeBag() }}
        @class([
            'fi-input-wrp',
            'fi-disabled' => $isDisabled(),
            'fi-invalid' => $field->hasValidationError(),
        ])
    >
        @if (filled($getSuffixLabel()))
            <div class="fi-persian-number-affix fi-persian-number-affix-start">
                <span class="fi-input-wrp-label">{{ $getSuffixLabel() }}</span>
            </div>
        @endif

        <div class="fi-input-wrp-content-ctn">
            <input
                x-bind:value="display"
                x-on:input="update($event)"
                x-on:blur="normalize()"
                id="{{ $getId() }}"
                type="text"
                inputmode="{{ $field->isInteger() ? 'numeric' : 'decimal' }}"
                @disabled($isDisabled())
                @readonly($isReadOnly())
                @required($isRequired())
                @if (filled($getPlaceholder()))
                    placeholder="{{ $getPlaceholder() }}"
                @endif
                {{ $getExtraInputAttributeBag() }}
                class="fi-input"
            />
        </div>

        @if ($showStepper)
            <div
                class="fi-input-wrp-suffix"
                style="flex-direction: column; justify-content: center; gap: 0; align-self: stretch; padding: 0 0.375rem 0 0.25rem;"
            >
                <button
                    type="button"
                    tabindex="-1"
                    x-on:click="increment()"
                    aria-label="افزایش"
                    class="fi-persian-number-stepper-button"
                >
                    <x-filament::icon icon="heroicon-m-chevron-up" style="width: 0.875rem; height: 0.875rem;" />
                </button>

                <button
                    type="button"
                    tabindex="-1"
                    x-on:click="decrement()"
                    aria-label="کاهش"
                    class="fi-persian-number-stepper-button"
                >
                    <x-filament::icon icon="heroicon-m-chevron-down" style="width: 0.875rem; height: 0.875rem;" />
                </button>
            </div>
        @endif

        @if (filled($getPrefixLabel()))
            <div class="fi-persian-number-affix fi-persian-number-affix-end">
                <span class="fi-input-wrp-label">{{ $getPrefixLabel() }}</span>
            </div>
        @endif
    </div>
</x-dynamic-component>
