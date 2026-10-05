@php
    // Re-open the modal when its own submission came back with errors.
    $consultationOpen = old('form_id') === 'consultation-modal' && $errors->any();
@endphp

<div class="consult-modal" id="consultation-modal" aria-hidden="{{ $consultationOpen ? 'false' : 'true' }}" @if ($consultationOpen) data-open="true" @endif>
    <div class="consult-modal__overlay" data-consult-close aria-hidden="true"></div>

    <div class="consult-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="consult-modal-title">
        <div class="consult-modal__header">
            <span class="consult-modal__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.184a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
            </span>
            <div class="consult-modal__heading">
                <h3 class="consult-modal__title" id="consult-modal-title">Book a Technical Consultation</h3>
                <p class="consult-modal__desc">Tell us about your project and a solution engineer will get back to you.</p>
            </div>
            <button type="button" class="consult-modal__close" data-consult-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="consult-modal__body">
            <x-public.enquiry-form form-id="consultation-modal" source="consultation" variant="modal" submit-label="Submit Request" />
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        var modal = document.getElementById('consultation-modal');
        if (!modal) { return; }

        var lastFocused = null;

        function openModal() {
            lastFocused = document.activeElement;
            var mobileMenu = document.getElementById('mobile-menu-overlay');
            if (mobileMenu) { mobileMenu.classList.remove('active'); }
            modal.setAttribute('data-open', 'true');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            var firstField = modal.querySelector('input, select, textarea');
            if (firstField) { firstField.focus(); }
        }

        function closeModal() {
            modal.removeAttribute('data-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (lastFocused) { lastFocused.focus(); }
        }

        document.querySelectorAll('.js-consult-open').forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                openModal();
            });
        });

        modal.querySelectorAll('[data-consult-close]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.hasAttribute('data-open')) {
                closeModal();
            }
        });

        // Keep body locked if the modal is pre-opened after a submit/validation error.
        if (modal.hasAttribute('data-open')) {
            document.body.style.overflow = 'hidden';
        }
    })();
</script>
@endpush
