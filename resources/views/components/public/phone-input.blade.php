@props([
    'id',
    'name' => 'phone',
    'countryName' => 'phone_country',
    'value' => null,
    'country' => null,
    'placeholder' => null,
    'inputClass' => '',
    'variant' => 'page',
])

@php
    // A phone number with a searchable country-code picker. The local number
    // and the ISO code are posted separately and combined server-side (see
    // ContactRequest), so the number still works without JavaScript.
    $codes = collect(\App\Support\Countries::phoneCodes());
    $selected = $codes->firstWhere('iso', strtoupper((string) $country)) ?? $codes->first();
    $menuId = $id.'-codes';
    $flag = fn (string $iso): string => asset('assets/images/flags/'.strtolower($iso).'.png');

    // Extra search terms so common short names and former names find the right country.
    $aliases = [
        'GB' => 'uk england britain great scotland wales', 'US' => 'usa america', 'AE' => 'uae emirates dubai abu dhabi',
        'KR' => 'korea', 'CI' => 'cote d\'ivoire', 'CZ' => 'czechia', 'SZ' => 'swaziland', 'MM' => 'burma',
        'TR' => 'turkiye', 'CV' => 'cabo verde', 'NL' => 'holland', 'DE' => 'deutschland', 'SA' => 'ksa',
    ];
    // Placeholder examples in each priority market's national format.
    $examples = [
        'IN' => '98765 43210', 'US' => '(201) 555-0123', 'AE' => '50 123 4567',
        'GB' => '7400 123456', 'CA' => '(506) 234-5678', 'DE' => '1512 3456789',
    ];
    $groups = $codes->groupBy(fn (array $code): string => $code['priority'] ? 'Suggested' : 'All countries');
@endphp

<div class="phone-field phone-field--{{ $variant }}" data-phone-field @if ($country) data-phone-explicit @endif @if ($placeholder) data-phone-fixed-placeholder @endif>
    <input type="hidden" name="{{ $countryName }}" value="{{ $selected['iso'] }}" data-phone-country>

    <button type="button" class="phone-field__trigger" data-phone-trigger
        aria-haspopup="listbox" aria-expanded="false" aria-controls="{{ $menuId }}-menu"
        aria-label="Country code: {{ $selected['name'] }} ({{ $selected['dial'] }})">
        <img class="phone-field__flag" src="{{ $flag($selected['iso']) }}" alt="" width="20" height="15" data-phone-flag>
        <span class="phone-field__dial" data-phone-dial>{{ $selected['dial'] }}</span>
        <svg class="phone-field__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
    </button>

    <input id="{{ $id }}" {{ $attributes->class(['phone-field__input', $inputClass => filled($inputClass)]) }} name="{{ $name }}" type="tel" inputmode="tel" autocomplete="tel"
        placeholder="{{ $placeholder ?? ($examples[$selected['iso']] ?? 'Phone number') }}" value="{{ $value }}" maxlength="20" data-phone-input>

    <div class="phone-field__menu" id="{{ $menuId }}-menu" tabindex="-1" data-phone-menu hidden>
        <div class="phone-field__search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" role="combobox" aria-expanded="true" aria-controls="{{ $menuId }}" aria-autocomplete="list"
                aria-label="Search countries" placeholder="Search country or code" autocomplete="off" spellcheck="false" data-phone-search>
        </div>

        <div class="phone-field__list" id="{{ $menuId }}" role="listbox" aria-label="Country codes" data-phone-list>
            @foreach ($groups as $label => $group)
                <div class="phone-field__group" role="group" aria-labelledby="{{ $menuId }}-group-{{ $loop->index }}" data-phone-group>
                    <div class="phone-field__group-label" id="{{ $menuId }}-group-{{ $loop->index }}">{{ $label }}</div>
                    @foreach ($group as $code)
                        <div class="phone-field__option" role="option" id="{{ $menuId }}-{{ strtolower($code['iso']) }}"
                            aria-selected="{{ $code['iso'] === $selected['iso'] ? 'true' : 'false' }}"
                            data-iso="{{ $code['iso'] }}" data-dial="{{ $code['dial'] }}" data-name="{{ $code['name'] }}"
                            data-example="{{ $examples[$code['iso']] ?? '' }}"
                            data-search="{{ strtolower($code['name'].' '.$code['iso'].' '.ltrim($code['dial'], '+').' '.($aliases[$code['iso']] ?? '')) }}">
                            <img class="phone-field__flag" src="{{ $flag($code['iso']) }}" alt="" width="20" height="15" loading="lazy">
                            <span class="phone-field__name">{{ $code['name'] }}</span>
                            <span class="phone-field__code">{{ $code['dial'] }}</span>
                            <svg class="phone-field__check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <p class="phone-field__empty" data-phone-empty hidden>No countries match your search.</p>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    (function () {
        // Visitors in a priority market get their dialling code preselected.
        var TIMEZONE_COUNTRIES = {
            'Asia/Kolkata': 'IN', 'Asia/Calcutta': 'IN', 'Asia/Dubai': 'AE', 'Europe/London': 'GB', 'Europe/Berlin': 'DE',
            'America/Toronto': 'CA', 'America/Vancouver': 'CA', 'America/Edmonton': 'CA', 'America/Winnipeg': 'CA',
            'America/Halifax': 'CA', 'America/St_Johns': 'CA', 'America/Regina': 'CA',
            'America/New_York': 'US', 'America/Chicago': 'US', 'America/Denver': 'US', 'America/Phoenix': 'US',
            'America/Los_Angeles': 'US', 'America/Anchorage': 'US', 'Pacific/Honolulu': 'US'
        };
        var finePointer = window.matchMedia && window.matchMedia('(pointer: fine)').matches;

        function guessCountry() {
            try {
                return TIMEZONE_COUNTRIES[Intl.DateTimeFormat().resolvedOptions().timeZone] || null;
            } catch (e) {
                return null;
            }
        }

        function initPhoneField(root) {
            var form = root.closest('form');
            var countrySelect = form ? form.querySelector('select[name="country"]') : null;
            var hidden = root.querySelector('[data-phone-country]');
            var trigger = root.querySelector('[data-phone-trigger]');
            var flag = root.querySelector('[data-phone-flag]');
            var dial = root.querySelector('[data-phone-dial]');
            var input = root.querySelector('[data-phone-input]');
            var menu = root.querySelector('[data-phone-menu]');
            var search = menu.querySelector('[data-phone-search]');
            var groups = Array.prototype.slice.call(menu.querySelectorAll('[data-phone-group]'));
            var options = Array.prototype.slice.call(menu.querySelectorAll('[role="option"]'));
            var empty = menu.querySelector('[data-phone-empty]');
            var fixedPlaceholder = root.hasAttribute('data-phone-fixed-placeholder');
            var userPicked = root.hasAttribute('data-phone-explicit');
            var visible = options;
            var activeIndex = -1;

            // Rendered at <body> level so the menu is never clipped by the modal
            // or by animated (transformed) page sections.
            document.body.appendChild(menu);

            function optionFor(attribute, value) {
                for (var i = 0; i < options.length; i++) {
                    if (options[i].getAttribute(attribute) === value) { return options[i]; }
                }
                return null;
            }

            function syncTriggerWidth() {
                if (trigger.offsetWidth) {
                    root.style.setProperty('--phone-trigger-w', trigger.offsetWidth + 'px');
                }
            }

            function choose(option) {
                options.forEach(function (o) { o.setAttribute('aria-selected', o === option ? 'true' : 'false'); });
                hidden.value = option.getAttribute('data-iso');
                flag.src = option.querySelector('img').getAttribute('src');
                dial.textContent = option.getAttribute('data-dial');
                trigger.setAttribute('aria-label', 'Country code: ' + option.getAttribute('data-name') + ' (' + option.getAttribute('data-dial') + ')');
                if (!fixedPlaceholder) {
                    input.placeholder = option.getAttribute('data-example') || 'Phone number';
                }
                syncTriggerWidth();
            }

            function pick(option) {
                choose(option);
                userPicked = true;
                if (countrySelect && !countrySelect.value) {
                    countrySelect.value = option.getAttribute('data-name');
                    countrySelect.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            function setActive(index, scroll) {
                if (activeIndex > -1 && visible[activeIndex]) { visible[activeIndex].classList.remove('is-active'); }
                activeIndex = Math.max(-1, Math.min(index, visible.length - 1));
                if (activeIndex === -1) {
                    search.removeAttribute('aria-activedescendant');
                    return;
                }
                var option = visible[activeIndex];
                option.classList.add('is-active');
                search.setAttribute('aria-activedescendant', option.id);
                if (scroll) { option.scrollIntoView({ block: 'nearest' }); }
            }

            function filter(query) {
                var term = query.trim().toLowerCase().replace(/^\+/, '');
                setActive(-1, false);
                visible = options.filter(function (o) {
                    var match = !term || (' ' + o.getAttribute('data-search')).indexOf(' ' + term) !== -1;
                    o.hidden = !match;
                    return match;
                });
                groups.forEach(function (group) {
                    group.hidden = !group.querySelector('[role="option"]:not([hidden])');
                    group.classList.toggle('is-searching', term !== '');
                });
                empty.hidden = visible.length > 0;
                setActive(term ? 0 : -1, false);
            }

            function position() {
                var field = root.getBoundingClientRect();
                var bounds = form ? form.getBoundingClientRect() : field;
                var viewportWidth = document.documentElement.clientWidth;
                var viewportHeight = window.innerHeight;
                var width = Math.min(Math.max(field.width, 300), viewportWidth - 16);
                // Stay within the form when the field sits in its right-hand column.
                var left = Math.min(field.left, Math.max(bounds.right, field.right) - width);
                left = Math.max(8, Math.min(left, viewportWidth - width - 8));
                var below = viewportHeight - field.bottom - 14;
                var above = field.top - 14;
                var placeAbove = below < 260 && above > below;

                menu.style.width = width + 'px';
                menu.style.left = left + 'px';
                menu.style.maxHeight = Math.min(360, placeAbove ? above : below) + 'px';
                menu.style.top = placeAbove ? '' : (field.bottom + 6) + 'px';
                menu.style.bottom = placeAbove ? (viewportHeight - field.top + 6) + 'px' : '';
                menu.classList.toggle('is-above', placeAbove);
            }

            function onViewportChange(e) {
                if (e.type === 'scroll' && menu.contains(e.target)) { return; }
                position();
            }

            function onOutsidePointer(e) {
                if (!root.contains(e.target) && !menu.contains(e.target)) { close(false); }
            }

            function open() {
                if (!menu.hidden) { return; }
                search.value = '';
                filter('');
                menu.hidden = false;
                root.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
                position();

                var selected = menu.querySelector('[aria-selected="true"]');
                setActive(visible.indexOf(selected), false);
                if (selected) {
                    var list = selected.closest('[data-phone-list]');
                    list.scrollTop = selected.offsetTop - list.clientHeight / 2 + selected.offsetHeight / 2;
                }
                // Touch devices: don't summon the on-screen keyboard over the list.
                (finePointer ? search : menu).focus({ preventScroll: true });

                document.addEventListener('pointerdown', onOutsidePointer, true);
                window.addEventListener('resize', onViewportChange);
                window.addEventListener('scroll', onViewportChange, true);
            }

            function close(focusTarget) {
                if (menu.hidden) { return; }
                menu.hidden = true;
                root.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
                setActive(-1, false);
                document.removeEventListener('pointerdown', onOutsidePointer, true);
                window.removeEventListener('resize', onViewportChange);
                window.removeEventListener('scroll', onViewportChange, true);
                if (focusTarget) { focusTarget.focus(); }
            }

            // "+971 50 123 4567" typed, pasted or autofilled into the number:
            // switch to that country and keep only the national part.
            function splitInternational() {
                var match = input.value.match(/^\s*(?:\+|00)\s*(.*)$/);
                if (!match) { return; }
                var digits = match[1].replace(/\D/g, '');
                // Dialling codes are prefix-free, so every candidate shares one code
                // (e.g. +1 for the US and Canada): keep the current pick if it is one.
                var candidates = options.filter(function (o) { return digits.indexOf(o.getAttribute('data-dial').slice(1)) === 0; });
                if (!candidates.length) { return; }
                var current = optionFor('data-iso', hidden.value);
                var country = candidates.indexOf(current) > -1 ? current : candidates[0];

                var rest = match[1];
                var codeDigits = country.getAttribute('data-dial').length - 1;
                while (rest && codeDigits > 0) {
                    if (/\d/.test(rest[0])) { codeDigits--; }
                    rest = rest.slice(1);
                }
                input.value = rest.replace(/^[\s\-.]*(\(0\))?[\s\-.]*/, '');
                pick(country);
            }

            trigger.addEventListener('click', function () {
                if (menu.hidden) { open(); } else { close(trigger); }
            });

            trigger.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    open();
                }
            });

            search.addEventListener('input', function () { filter(search.value); });

            menu.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    setActive(Math.max(0, activeIndex + (e.key === 'ArrowDown' ? 1 : -1)), true);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (visible[activeIndex]) {
                        pick(visible[activeIndex]);
                        close(input);
                    }
                } else if (e.key === 'Escape') {
                    // Close only the menu, not the consultation modal around it.
                    e.preventDefault();
                    e.stopPropagation();
                    close(trigger);
                } else if (e.key === 'Tab') {
                    e.preventDefault();
                    close(e.shiftKey ? trigger : input);
                }
            });

            menu.addEventListener('mousemove', function (e) {
                var option = e.target.closest('[role="option"]');
                if (option && visible.indexOf(option) !== activeIndex) { setActive(visible.indexOf(option), false); }
            });

            menu.addEventListener('click', function (e) {
                var option = e.target.closest('[role="option"]');
                if (option) {
                    pick(option);
                    close(input);
                }
            });

            input.addEventListener('input', function (e) {
                if (!e.inputType || e.inputType === 'insertFromPaste' || e.inputType === 'insertReplacementText') { splitInternational(); }
            });
            input.addEventListener('change', splitInternational);

            if (countrySelect) {
                countrySelect.addEventListener('change', function () {
                    var option = optionFor('data-name', countrySelect.value);
                    if (!userPicked && option) { choose(option); }
                });
            }

            // Starting code: an explicit one wins, then the chosen country, then the visitor's timezone.
            if (!userPicked) {
                var initial = (countrySelect && optionFor('data-name', countrySelect.value)) || optionFor('data-iso', guessCountry());
                if (initial) { choose(initial); }
            }
            splitInternational();

            // The field may be hidden (closed modal) when this runs: re-measure once it renders.
            if (window.ResizeObserver) {
                new ResizeObserver(syncTriggerWidth).observe(trigger);
            }
            syncTriggerWidth();
        }

        document.querySelectorAll('[data-phone-field]').forEach(initPhoneField);
    })();
</script>
@endpush
@endonce
