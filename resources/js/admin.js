// Admin panel scripts.

// Settings tabs: show one panel at a time, persist choice in the URL hash.
(function initSettingsTabs() {
    const tabs = () => [...document.querySelectorAll('[data-settings-tab]')];
    const panels = () => [...document.querySelectorAll('[data-settings-panel]')];

    function activateTab(group) {
        tabs().forEach((btn) => {
            const isActive = btn.dataset.settingsTab === group;
            btn.classList.toggle('border-fuchsia-500', isActive);
            btn.classList.toggle('bg-fuchsia-50', isActive);
            btn.classList.toggle('text-fuchsia-700', isActive);
            btn.classList.toggle('border-slate-200', !isActive);
            btn.classList.toggle('bg-white', !isActive);
            btn.classList.toggle('text-slate-600', !isActive);
        });
        panels().forEach((panel) => {
            panel.hidden = panel.dataset.settingsPanel !== group;
        });
        history.replaceState(null, '', '#' + group);
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-settings-tab]');
        if (btn) {
            activateTab(btn.dataset.settingsTab);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const all = tabs();
        if (!all.length) {
            return;
        }
        const hash = location.hash.replace('#', '');
        const initial = all.find((b) => b.dataset.settingsTab === hash) ?? all[0];
        activateTab(initial.dataset.settingsTab);
    });
})();


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

    // Image fields explicitly target a preview by selector; repeater rows fall
    // back to the preview image within the same [data-image-field].
    const preview = input.dataset.preview
        ? document.querySelector(input.dataset.preview)
        : input.closest('[data-image-field]')?.querySelector('[data-preview-img]');
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

// Client-side table filter for [data-table-search] inputs. The value of the
// attribute is a selector for the table whose tbody rows are filtered by the
// typed term. Empty-state rows (a single td[colspan]) are never hidden.
document.addEventListener('input', (e) => {
    const input = e.target.closest('[data-table-search]');
    if (!input) {
        return;
    }

    const table = document.querySelector(input.dataset.tableSearch);
    if (!table) {
        return;
    }

    const term = input.value.trim().toLowerCase();
    table.querySelectorAll('tbody tr').forEach((row) => {
        if (row.querySelector('td[colspan]')) {
            return;
        }
        row.style.display = !term || row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});

// Repeater rows: add / remove / reorder. New rows are cloned from the
// [data-repeater-template] with a unique, never-reused index so file/hidden
// inputs in the same row stay associated. Order follows DOM order; the server
// reindexes and drops empty rows on save.
document.addEventListener('click', (e) => {
    const addButton = e.target.closest('[data-repeater-add]');
    if (addButton) {
        const repeater = addButton.closest('[data-repeater]');
        const template = repeater?.querySelector('[data-repeater-template]');
        const rows = repeater?.querySelector('[data-repeater-rows]');
        if (!template || !rows) {
            return;
        }

        const next = Number(repeater.dataset.nextIndex ?? 100000);
        repeater.dataset.nextIndex = String(next + 1);

        const html = template.innerHTML.replaceAll('__INDEX__', String(next));
        rows.appendChild(document.createRange().createContextualFragment(html));
        return;
    }

    const removeButton = e.target.closest('[data-repeater-remove]');
    if (removeButton) {
        removeButton.closest('[data-repeater-row]')?.remove();
        return;
    }

    const upButton = e.target.closest('[data-repeater-up]');
    if (upButton) {
        const row = upButton.closest('[data-repeater-row]');
        const previous = row?.previousElementSibling;
        if (row && previous) {
            row.parentNode.insertBefore(row, previous);
        }
        return;
    }

    const downButton = e.target.closest('[data-repeater-down]');
    if (downButton) {
        const row = downButton.closest('[data-repeater-row]');
        const next = row?.nextElementSibling;
        if (row && next) {
            row.parentNode.insertBefore(next, row);
        }
    }
});
