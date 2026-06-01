// Admin panel scripts.
// Mobile sidebar toggle + backdrop.
const sidebar = () => document.querySelector('[data-sidebar]');
const backdrop = () => document.querySelector('[data-sidebar-backdrop]');

const openSidebar = () => {
    sidebar()?.classList.remove('-translate-x-full');
    backdrop()?.classList.remove('hidden');
};

const closeSidebar = () => {
    sidebar()?.classList.add('-translate-x-full');
    backdrop()?.classList.add('hidden');
};

document.addEventListener('click', (e) => {
    if (e.target.closest('[data-sidebar-toggle]')) {
        sidebar()?.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
        return;
    }

    if (e.target.closest('[data-sidebar-backdrop]')) {
        closeSidebar();
    }
});

// Live image preview for file inputs marked with [data-image-input].
document.addEventListener('change', (e) => {
    const input = e.target.closest('[data-image-input]');
    if (!input || !input.files?.length) {
        return;
    }

    const preview = document.querySelector(input.dataset.preview);
    if (preview) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');
        preview.parentElement?.querySelector('[data-placeholder]')?.classList.add('hidden');
    }

    // Picking a new file cancels any pending removal.
    const field = input.closest('[data-image-field]');
    field?.querySelector('[data-remove-flag]')?.setAttribute('value', '0');
    field?.querySelector('[data-remove-image]')?.classList.remove('hidden');
});

// Remove image: flag it for deletion and reset the preview to the placeholder.
document.addEventListener('click', (e) => {
    const button = e.target.closest('[data-remove-image]');
    if (!button) {
        return;
    }

    const field = button.closest('[data-image-field]');
    if (!field) {
        return;
    }

    field.querySelector('[data-remove-flag]')?.setAttribute('value', '1');

    const fileInput = field.querySelector('[data-image-input]');
    if (fileInput) {
        fileInput.value = '';
    }

    const preview = field.querySelector('[data-preview-img]');
    if (preview) {
        preview.src = '';
        preview.classList.add('hidden');
    }

    field.querySelector('[data-placeholder]')?.classList.remove('hidden');
    field.querySelector('[data-current-name]')?.classList.add('hidden');
    button.classList.add('hidden');
});
