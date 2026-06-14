<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tricrete Systems">
    <title><?= esc($pageTitle ?? 'Dashboard') ?> — Tricrete Systems</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<?= rawurlencode(svg_logo_mark('', '32')) ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- DataTables + Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"
        rel="stylesheet">

    <!-- Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
        rel="stylesheet">

    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        rel="stylesheet">

    <!-- App CSS -->
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">

    <?= $this->renderSection('styles') ?>
</head>

<body>

    <!-- Mobile overlay -->
    <div id="tsOverlay" class="ts-overlay"></div>

    <div class="ts-wrapper">

        <!-- Sidebar -->
        <?= $this->include('partials/sidebar') ?>

        <!-- Main -->
        <div id="tsMain" class="ts-main">

            <!-- Topbar -->
            <?= $this->include('partials/topbar') ?>

            <!-- Flash messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible auto-dismiss fade show mx-4 mt-3 mb-0 py-2"
                    style="font-size:13px;" role="alert">
                    <?= svg_icon('check', 'me-1', '15') ?>
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible auto-dismiss fade show mx-4 mt-3 mb-0 py-2"
                    style="font-size:13px;" role="alert">
                    <?= svg_icon('alert', 'me-1', '15') ?>
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page content -->
            <main class="ts-content">
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Footer -->
            <footer class="ts-footer">
                &copy; <?= date('Y') ?> Tricrete Systems &mdash;
                Conrete Products Manufacturing. All rights reserved.
            </footer>

        </div>

    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- App JS -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>