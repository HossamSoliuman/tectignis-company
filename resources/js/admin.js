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


// Mobile sidebar: opens as a drawer over the page. It closes from the close
// button, the backdrop, Escape or a link inside it, and resets when the
// window grows to the desktop layout where it is always shown.
const sidebar = () => document.querySelector('[data-sidebar]');
const backdrop = () => document.querySelector('[data-sidebar-backdrop]');
const sidebarToggle = () => document.querySelector('[data-sidebar-toggle]');
const desktopLayout = window.matchMedia('(min-width: 1024px)');
const isSidebarOpen = () => sidebar()?.classList.contains('-translate-x-full') === false;

const setSidebarOpen = (open) => {
    sidebar()?.classList.toggle('-translate-x-full', !open);
    sidebar()?.classList.toggle('invisible', !open);
    backdrop()?.classList.toggle('hidden', !open);
    document.body.classList.toggle('overflow-hidden', open);
    sidebarToggle()?.setAttribute('aria-expanded', String(open));
};

document.addEventListener('click', (e) => {
    if (e.target.closest('[data-sidebar-toggle]')) {
        setSidebarOpen(!isSidebarOpen());
        if (isSidebarOpen()) {
            sidebar()?.querySelector('[data-sidebar-close]')?.focus();
        }
        return;
    }

    if (e.target.closest('[data-sidebar-close], [data-sidebar-backdrop]')) {
        setSidebarOpen(false);
        sidebarToggle()?.focus();
        return;
    }

    if (e.target.closest('[data-sidebar] a') && !desktopLayout.matches) {
        setSidebarOpen(false);
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isSidebarOpen() && !desktopLayout.matches) {
        setSidebarOpen(false);
        sidebarToggle()?.focus();
    }
});

desktopLayout.addEventListener('change', (e) => {
    if (e.matches && isSidebarOpen()) {
        setSidebarOpen(false);
    }
});

// Long menus push the current page below the fold; bring it into view.
document.addEventListener('DOMContentLoaded', () => {
    const nav = document.querySelector('[data-sidebar] nav');
    const current = nav?.querySelector('[aria-current="page"]');
    if (!nav || !current) {
        return;
    }

    const navBox = nav.getBoundingClientRect();
    const currentBox = current.getBoundingClientRect();
    if (currentBox.top < navBox.top || currentBox.bottom > navBox.bottom) {
        nav.scrollTop += currentBox.top - navBox.top - (nav.clientHeight - currentBox.height) / 2;
    }
});

// A page restored from the back/forward cache keeps the drawer as it was left.
window.addEventListener('pageshow', (e) => {
    if (e.persisted && isSidebarOpen()) {
        setSidebarOpen(false);
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

// Bulk selection: [data-bulk-all] toggles every [data-bulk-item] inside the
// [data-bulk-scope]; the [data-bulk-bar] shows only while something is selected.
(function initBulkSelection() {
    const items = () => [...document.querySelectorAll('[data-bulk-scope] [data-bulk-item]')];

    function sync() {
        const all = items();
        const selected = all.filter((item) => item.checked).length;
        const bar = document.querySelector('[data-bulk-bar]');
        const toggle = document.querySelector('[data-bulk-scope] [data-bulk-all]');

        if (bar) {
            bar.hidden = selected === 0;
            bar.querySelector('[data-bulk-count]').textContent = String(selected);
        }

        if (toggle) {
            toggle.checked = all.length > 0 && selected === all.length;
            toggle.indeterminate = selected > 0 && selected < all.length;
        }
    }

    document.addEventListener('change', (e) => {
        if (e.target.closest('[data-bulk-all]')) {
            items().forEach((item) => {
                item.checked = e.target.checked;
            });
            sync();
            return;
        }

        if (e.target.closest('[data-bulk-item]')) {
            sync();
        }
    });

    // Browsers may restore checkbox state on back navigation.
    window.addEventListener('pageshow', sync);
})();

// Drag-and-drop table rows: grab a [data-reorder-handle] (or focus it and use
// the arrow keys) and the new order is posted to the list's data-reorder-url.
// Neighbouring rows move around the dragged one, so it never leaves the DOM
// and keeps its pointer capture.
(function initRowReorder() {
    const DRAGGING_CLASSES = ['bg-fuchsia-50!', 'shadow-md'];
    const SCROLL_EDGE = 90;
    const SCROLL_STEP = 14;

    const confirmedOrder = new WeakMap();
    let drag = null;
    let saves = Promise.resolve();
    let toastTimer = null;

    const rowsOf = (list) => [...list.querySelectorAll('[data-reorder-id]')];
    const idsOf = (rows) => rows.map((row) => Number(row.dataset.reorderId));
    const midpointOf = (row) => {
        const box = row.getBoundingClientRect();
        return box.top + box.height / 2;
    };

    const rememberOrder = (list) => {
        if (!confirmedOrder.has(list)) {
            confirmedOrder.set(list, rowsOf(list));
        }
    };

    const showToast = (message, failed = false) => {
        const toast = document.querySelector('[data-reorder-toast]');
        if (!toast) {
            return;
        }
        toast.textContent = message;
        toast.classList.remove('hidden');
        toast.classList.toggle('bg-rose-600', failed);
        toast.classList.toggle('bg-slate-900', !failed);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('hidden'), 2500);
    };

    const persist = (list) => {
        const rows = rowsOf(list);

        saves = saves
            .then(() =>
                fetch(list.dataset.reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ ids: idsOf(rows) }),
                }),
            )
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Reorder failed with status ${response.status}`);
                }
                return response.json();
            })
            .then(({ order }) => {
                rows.forEach((row) => {
                    row.querySelector('[data-reorder-position]').textContent = order[row.dataset.reorderId];
                });
                confirmedOrder.set(list, rows);
                showToast('Order saved');
            })
            .catch(() => {
                confirmedOrder.get(list).forEach((row) => list.appendChild(row));
                showToast('The order could not be saved. Please try again.', true);
            });
    };

    const followPointer = (row, list, pointerY) => {
        let sibling = row.previousElementSibling;
        while (sibling && pointerY < midpointOf(sibling)) {
            list.insertBefore(sibling, row.nextElementSibling);
            sibling = row.previousElementSibling;
        }

        sibling = row.nextElementSibling;
        while (sibling && pointerY > midpointOf(sibling)) {
            list.insertBefore(sibling, row);
            sibling = row.nextElementSibling;
        }
    };

    document.addEventListener('pointerdown', (e) => {
        const handle = e.target.closest('[data-reorder-handle]');
        if (!handle || e.button !== 0) {
            return;
        }

        const row = handle.closest('[data-reorder-id]');
        const list = row.closest('[data-reorder]');
        e.preventDefault();
        rememberOrder(list);
        handle.setPointerCapture(e.pointerId);
        row.classList.add(...DRAGGING_CLASSES);
        drag = { row, list, startOrder: idsOf(rowsOf(list)).join() };
    });

    document.addEventListener('pointermove', (e) => {
        if (!drag) {
            return;
        }

        followPointer(drag.row, drag.list, e.clientY);

        if (e.clientY < SCROLL_EDGE) {
            window.scrollBy(0, -SCROLL_STEP);
        } else if (e.clientY > window.innerHeight - SCROLL_EDGE) {
            window.scrollBy(0, SCROLL_STEP);
        }
    });

    const finishDrag = () => {
        if (!drag) {
            return;
        }

        const { row, list, startOrder } = drag;
        drag = null;
        row.classList.remove(...DRAGGING_CLASSES);

        if (idsOf(rowsOf(list)).join() !== startOrder) {
            persist(list);
        }
    };

    document.addEventListener('pointerup', finishDrag);
    document.addEventListener('pointercancel', finishDrag);

    document.addEventListener('keydown', (e) => {
        const handle = e.target.closest?.('[data-reorder-handle]');
        if (!handle || !['ArrowUp', 'ArrowDown'].includes(e.key)) {
            return;
        }

        e.preventDefault();
        const row = handle.closest('[data-reorder-id]');
        const list = row.closest('[data-reorder]');
        const sibling = e.key === 'ArrowUp' ? row.previousElementSibling : row.nextElementSibling;
        if (!sibling) {
            return;
        }

        rememberOrder(list);
        list.insertBefore(sibling, e.key === 'ArrowUp' ? row.nextElementSibling : row);
        persist(list);
    });
})();
