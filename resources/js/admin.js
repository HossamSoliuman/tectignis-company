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
});
