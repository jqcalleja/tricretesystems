<header class="ts-topbar">

    <button id="btnSidebarToggle" class="ts-btn-toggle" aria-label="Toggle sidebar">
        <?= svg_icon('menu', '', '20') ?>
    </button>

    <div class="ts-topbar-title">
        <?= esc($pageTitle ?? 'Dashboard') ?>
    </div>

    <div class="ts-topbar-right">

        <span id="tsClock" class="ts-clock"></span>

        <button class="ts-icon-btn" title="Notifications">
            <?= svg_icon('bell', '', '20') ?>
        </button>

        <div class="ts-user-menu dropdown">
            <button class="dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="ts-user-avatar">
                    <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
                </div>
                <span><?= esc(session()->get('user_name') ?? 'Administrator') ?></span>
                <?= svg_icon('chevron-down', '', '14') ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                style="font-size:13px; min-width:185px;">
                <li>
                    <a class="dropdown-item py-2" href="<?= base_url('/profile') ?>">
                        <?= svg_icon('user-circle', 'me-2', '15') ?>My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="<?= base_url('/settings') ?>">
                        <?= svg_icon('settings', 'me-2', '15') ?>Settings
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item py-2 text-danger"
                        href="<?= base_url('/auth/logout') ?>">
                        <?= svg_icon('logout', 'me-2', '15') ?>Sign Out
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>