<?php
$uri     = service('uri');
$segment = $uri->getSegment(1) ?? '';
?>
<aside id="tsSidebar" class="ts-sidebar">

    <!-- Brand -->
    <a href="<?= base_url('/') ?>" class="ts-sidebar-brand">
        <?= svg_logo_mark('', '34') ?>
        <div class="ts-brand-text">
            <span class="ts-brand-name">Tricrete Systems</span>
            <span class="ts-brand-sub">Conrete Products Mfg.</span>
        </div>
    </a>

    <!-- HR Module -->
    <span class="ts-nav-label">HR Management</span>
    <ul class="ts-nav">
        <li class="ts-nav-item">
            <a href="<?= base_url('/dashboard') ?>"
                class="ts-nav-link <?= in_array($segment, ['', 'dashboard']) ? 'active' : '' ?>"
                data-label="Dashboard">
                <span class="nav-icon"><?= svg_icon('dashboard', '', '18') ?></span>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/employees') ?>"
                class="ts-nav-link <?= $segment === 'employees' ? 'active' : '' ?>"
                data-label="Employees">
                <span class="nav-icon"><?= svg_icon('employees', '', '18') ?></span>
                <span class="nav-label">Employees</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/attendance') ?>"
                class="ts-nav-link <?= $segment === 'attendance' ? 'active' : '' ?>"
                data-label="Attendance">
                <span class="nav-icon"><?= svg_icon('attendance', '', '18') ?></span>
                <span class="nav-label">Attendance (DTR)</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/projects') ?>"
                class="ts-nav-link <?= $segment === 'projects' ? 'active' : '' ?>"
                data-label="Projects">
                <span class="nav-icon"><?= svg_icon('projects', '', '18') ?></span>
                <span class="nav-label">Projects</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/reports') ?>"
                class="ts-nav-link <?= $segment === 'reports' ? 'active' : '' ?>"
                data-label="Reports">
                <span class="nav-icon"><?= svg_icon('reports', '', '18') ?></span>
                <span class="nav-label">Reports</span>
            </a>
        </li>
    </ul>

    <!-- Tools & Equipment Module -->
    <span class="ts-nav-label">Tools &amp; Equipment</span>
    <ul class="ts-nav">
        <li class="ts-nav-item">
            <a href="<?= base_url('/equipment') ?>"
                class="ts-nav-link <?= $segment === 'equipment' ? 'active' : '' ?>"
                data-label="Equipment">
                <span class="nav-icon"><?= svg_icon('equipment', '', '18') ?></span>
                <span class="nav-label">Equipment</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/inventory') ?>"
                class="ts-nav-link <?= $segment === 'inventory' ? 'active' : '' ?>"
                data-label="Inventory">
                <span class="nav-icon"><?= svg_icon('inventory', '', '18') ?></span>
                <span class="nav-label">Inventory</span>
            </a>
        </li>
    </ul>

    <!-- Accounting Module -->
    <span class="ts-nav-label">Accounting</span>
    <ul class="ts-nav">
        <li class="ts-nav-item">
            <a href="<?= base_url('/accounting') ?>"
                class="ts-nav-link <?= $segment === 'accounting' ? 'active' : '' ?>"
                data-label="Accounting">
                <span class="nav-icon"><?= svg_icon('accounting', '', '18') ?></span>
                <span class="nav-label">Accounting</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/journal') ?>"
                class="ts-nav-link <?= $segment === 'journal' ? 'active' : '' ?>"
                data-label="Journal Entries">
                <span class="nav-icon"><?= svg_icon('journal', '', '18') ?></span>
                <span class="nav-label">Journal Entries</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/accounts') ?>"
                class="ts-nav-link <?= $segment === 'accounts' ? 'active' : '' ?>"
                data-label="Chart of Accounts">
                <span class="nav-icon"><?= svg_icon('chart-of-accounts', '', '18') ?></span>
                <span class="nav-label">Chart of Accounts</span>
            </a>
        </li>
    </ul>

    <!-- System -->
    <span class="ts-nav-label">System</span>
    <ul class="ts-nav">
        <li class="ts-nav-item">
            <a href="<?= base_url('/users') ?>"
                class="ts-nav-link <?= $segment === 'users' ? 'active' : '' ?>"
                data-label="User Accounts">
                <span class="nav-icon"><?= svg_icon('users', '', '18') ?></span>
                <span class="nav-label">User Accounts</span>
            </a>
        </li>
        <li class="ts-nav-item">
            <a href="<?= base_url('/settings') ?>"
                class="ts-nav-link <?= $segment === 'settings' ? 'active' : '' ?>"
                data-label="Settings">
                <span class="nav-icon"><?= svg_icon('settings', '', '18') ?></span>
                <span class="nav-label">Settings</span>
            </a>
        </li>
    </ul>

    <!-- Sidebar footer -->
    <div class="ts-sidebar-footer">
        <div class="footer-avatar">
            <?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?>
        </div>
        <div class="footer-info">
            <div class="footer-name"><?= esc(session()->get('user_name') ?? 'Administrator') ?></div>
            <div class="footer-role"><?= esc(session()->get('user_role') ?? '') ?></div>
        </div>
    </div>

</aside>