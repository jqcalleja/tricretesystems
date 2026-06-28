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
            <span class="ts-brand-sub">By: Tricrete Construction</span>
        </div>
    </a>

    <!-- HR Module -->
    <div class="ts-nav-group">
        <button type="button" class="ts-nav-label ts-nav-label-toggle" data-target="navGroupHR">
            <span>HR Management</span>
            <span class="ts-nav-label-chevron"><?= svg_icon('chevron-down', '', '12') ?></span>
        </button>
        <div class="ts-nav" id="navGroupHR" role="list">
            <div class="ts-nav-collapse-inner">
                <div class="ts-nav-item" role="listitem">
                    <a href="<?= base_url('/dashboard') ?>"
                        class="ts-nav-link <?= in_array($segment, ['', 'dashboard']) ? 'active' : '' ?>"
                        data-label="Dashboard">
                        <span class="nav-icon"><?= svg_icon('dashboard', '', '18') ?></span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <a href="<?= base_url('/employees') ?>"
                        class="ts-nav-link <?= $segment === 'employees' ? 'active' : '' ?>"
                        data-label="Employees">
                        <span class="nav-icon"><?= svg_icon('employees', '', '18') ?></span>
                        <span class="nav-label">Employees</span>
                    </a>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Attendance (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('attendance', '', '18') ?></span>
                        <span class="nav-label">Attendance (DTR)</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <a href="<?= base_url('/projects') ?>"
                        class="ts-nav-link <?= $segment === 'projects' ? 'active' : '' ?>"
                        data-label="Projects">
                        <span class="nav-icon"><?= svg_icon('projects', '', '18') ?></span>
                        <span class="nav-label">Projects</span>
                    </a>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Reports (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('reports', '', '18') ?></span>
                        <span class="nav-label">Reports</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tools & Equipment Module -->
    <div class="ts-nav-group ts-nav-group-disabled">
        <button type="button" class="ts-nav-label ts-nav-label-toggle" data-target="navGroupEquipment">
            <span>Tools &amp; Equipment</span>
            <span class="ts-nav-group-status">Soon</span>
            <span class="ts-nav-label-chevron"><?= svg_icon('chevron-down', '', '12') ?></span>
        </button>
        <div class="ts-nav" id="navGroupEquipment" role="list">
            <div class="ts-nav-collapse-inner">
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Equipment (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('equipment', '', '18') ?></span>
                        <span class="nav-label">Equipment</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Inventory (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('inventory', '', '18') ?></span>
                        <span class="nav-label">Inventory</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounting Module -->
    <div class="ts-nav-group ts-nav-group-disabled">
        <button type="button" class="ts-nav-label ts-nav-label-toggle" data-target="navGroupAccounting">
            <span>Accounting</span>
            <span class="ts-nav-group-status">Soon</span>
            <span class="ts-nav-label-chevron"><?= svg_icon('chevron-down', '', '12') ?></span>
        </button>
        <div class="ts-nav" id="navGroupAccounting" role="list">
            <div class="ts-nav-collapse-inner">
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Accounting (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('accounting', '', '18') ?></span>
                        <span class="nav-label">Accounting</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Journal Entries (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('journal', '', '18') ?></span>
                        <span class="nav-label">Journal Entries</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="Chart of Accounts (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('chart-of-accounts', '', '18') ?></span>
                        <span class="nav-label">Chart of Accounts</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- System -->
    <div class="ts-nav-group">
        <button type="button" class="ts-nav-label ts-nav-label-toggle" data-target="navGroupSystem">
            <span>System</span>
            <span class="ts-nav-label-chevron"><?= svg_icon('chevron-down', '', '12') ?></span>
        </button>
        <div class="ts-nav" id="navGroupSystem" role="list">
            <div class="ts-nav-collapse-inner">
                <div class="ts-nav-item" role="listitem">
                    <span class="ts-nav-link is-disabled"
                        role="link"
                        aria-disabled="true"
                        data-label="User Accounts (Coming soon)">
                        <span class="nav-icon"><?= svg_icon('users', '', '18') ?></span>
                        <span class="nav-label">User Accounts</span>
                        <span class="nav-status">Soon</span>
                    </span>
                </div>
                <div class="ts-nav-item" role="listitem">
                    <a href="<?= base_url('/settings') ?>"
                        class="ts-nav-link <?= $segment === 'settings' ? 'active' : '' ?>"
                        data-label="Settings">
                        <span class="nav-icon"><?= svg_icon('settings', '', '18') ?></span>
                        <span class="nav-label">Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

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
