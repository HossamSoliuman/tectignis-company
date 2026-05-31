// Admin panel scripts.
// Mobile sidebar toggle.
document.addEventListener('click', (e) => {
    const toggle = e.target.closest('[data-sidebar-toggle]');
    if (toggle) {
        document.querySelector('[data-sidebar]')?.classList.toggle('-translate-x-full');
    }
});
