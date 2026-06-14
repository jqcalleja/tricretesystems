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
    // 5. SweetAlert2 delete confirmation
    // ----------------------------------------------------------
    document.querySelectorAll('[data-confirm-delete]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const target = btn.getAttribute('data-confirm-delete') || btn.getAttribute('href');
            const label = btn.getAttribute('data-label') || 'this record';
            Swal.fire({
                title: 'Delete ' + label + '?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it',
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

});