/**
 * Tricrete Systems — Global JS
 */
document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------
    // 1. Sidebar toggle
    // ----------------------------------------------------------
    const sidebar = document.getElementById('tsSidebar');
    const mainArea = document.getElementById('tsMain');
    const overlay = document.getElementById('tsOverlay');
    const btnToggle = document.getElementById('btnSidebarToggle');

    if (btnToggle && sidebar) {
        btnToggle.addEventListener('click', function () {
            const isMobile = window.innerWidth < 992;
            if (isMobile) {
                sidebar.classList.toggle('mobile-open');
                overlay && overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                mainArea && mainArea.classList.toggle('expanded');
                localStorage.setItem(
                    'ts_sidebar_collapsed',
                    sidebar.classList.contains('collapsed') ? '1' : '0'
                );
            }
        });
    }

    // Restore sidebar state on desktop
    if (sidebar && window.innerWidth >= 992) {
        if (localStorage.getItem('ts_sidebar_collapsed') === '1') {
            sidebar.classList.add('collapsed');
            mainArea && mainArea.classList.add('expanded');
        }
    }

    // Close on overlay click (mobile)
    overlay && overlay.addEventListener('click', function () {
        sidebar && sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    });

    // ----------------------------------------------------------
    // 2. Live clock
    // ----------------------------------------------------------
    const clockEl = document.getElementById('tsClock');
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    function updateClock() {
        if (!clockEl) return;
        const now = new Date();
        let hours = now.getHours();
        const mins = String(now.getMinutes()).padStart(2, '0');
        const secs = String(now.getSeconds()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = String(hours % 12 || 12).padStart(2, '0');
        clockEl.textContent =
            `${days[now.getDay()]}, ${months[now.getMonth()]} `
            + `${String(now.getDate()).padStart(2, '0')}, ${now.getFullYear()} `
            + `\u00A0 ${hours}:${mins}:${secs} ${ampm}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ----------------------------------------------------------
    // 3. Active nav link
    // ----------------------------------------------------------
    const currentPath = window.location.pathname;
    document.querySelectorAll('.ts-nav-link').forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && href !== '/' && currentPath.startsWith(href)) {
            link.classList.add('active');
        }
    });

    // ----------------------------------------------------------
    // 4. Auto-dismiss alerts
    // ----------------------------------------------------------
    document.querySelectorAll('.alert-dismissible.auto-dismiss').forEach(function (el) {
        setTimeout(function () {
            bootstrap.Alert.getOrCreateInstance(el)?.close();
        }, 4000);
    });

    // ----------------------------------------------------------
    // 5. SweetAlert2 confirmation (delete, deactivate, activate, generic)
    // ----------------------------------------------------------
    document.querySelectorAll('[data-confirm-delete]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const target = btn.getAttribute('data-confirm-delete') || btn.getAttribute('href');
            const label = btn.getAttribute('data-label') || 'this record';
            const type = btn.getAttribute('data-confirm-type') || 'delete';

            const presets = {
                delete: {
                    title: 'Delete ' + label + '?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'Yes, delete it',
                },
                deactivate: {
                    title: 'Deactivate ' + label + '?',
                    text: 'They will be marked as inactive and hidden from active lists.',
                    icon: 'warning',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'Yes, deactivate',
                },
                activate: {
                    title: 'Activate ' + label + '?',
                    text: 'They will be marked as active again.',
                    icon: 'question',
                    confirmButtonColor: '#EE2B2B',
                    confirmButtonText: 'Yes, activate',
                },
                generic: {
                    title: label + '?',
                    text: 'Please confirm this action.',
                    icon: 'question',
                    confirmButtonColor: '#EE2B2B',
                    confirmButtonText: 'Yes, continue',
                },
            };

            const preset = presets[type] || presets.generic;

            Swal.fire({
                title: preset.title,
                text: preset.text,
                icon: preset.icon,
                showCancelButton: true,
                confirmButtonColor: preset.confirmButtonColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: preset.confirmButtonText,
                cancelButtonText: 'Cancel',
            }).then(function (result) {
                if (result.isConfirmed) window.location.href = target;
            });
        });
    });

    // ----------------------------------------------------------
    // 6. Toastr defaults
    // ----------------------------------------------------------
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3500,
        };
    }

    // ----------------------------------------------------------
    // 7. Global uppercase enforcement on text inputs
    // ----------------------------------------------------------
    function shouldUppercase(el) {
        if (el.classList.contains('ts-no-uppercase')) return false;
        if (el.type === 'password') return false;
        if (el.type === 'email') return false; // emails are conventionally lowercase
        if (el.type === 'hidden') return false;
        return el.tagName === 'TEXTAREA'
            || el.type === 'text'
            || el.type === 'search'
            || el.type === 'tel';
    }

    document.querySelectorAll('input, textarea').forEach(function (el) {
        if (!shouldUppercase(el)) return;

        el.addEventListener('input', function () {
            const start = el.selectionStart;
            const end = el.selectionEnd;
            el.value = el.value.toUpperCase();
            // restore cursor position so typing doesn't jump
            el.setSelectionRange(start, end);
        });
    });

    // Also catch inputs added dynamically later (e.g. modal forms, combobox fields)
    document.addEventListener('input', function (e) {
        const el = e.target;
        if (!(el.tagName === 'INPUT' || el.tagName === 'TEXTAREA')) return;
        if (!shouldUppercase(el)) return;
        if (el.dataset.tsUppercaseBound) return; // already handled by direct listener above

        const start = el.selectionStart;
        const end = el.selectionEnd;
        el.value = el.value.toUpperCase();
        el.setSelectionRange(start, end);
    }, true);

    // ----------------------------------------------------------
    // 8. Collapsible nav groups (independent of sidebar minimize)
    // ----------------------------------------------------------
    document.querySelectorAll('.ts-nav-label-toggle').forEach(function (btn) {
        const targetId = btn.getAttribute('data-target');
        const targetEl = document.getElementById(targetId);
        if (!targetEl) return;

        // Restore saved collapsed state
        const savedState = localStorage.getItem('ts_navgroup_' + targetId);
        if (savedState === 'collapsed') {
            btn.classList.add('collapsed');
            targetEl.classList.add('collapsed');
        }

        btn.addEventListener('click', function () {
            const isCollapsed = btn.classList.toggle('collapsed');
            targetEl.classList.toggle('collapsed', isCollapsed);
            localStorage.setItem('ts_navgroup_' + targetId, isCollapsed ? 'collapsed' : 'expanded');
        });
    });

});