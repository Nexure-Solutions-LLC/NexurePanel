<?php

    function isActive($page) {

        $currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return ($currentPage === $page) ? 'active' : '';

    }

?>

<aside class="caliweb-sidebar">
    <ul class="sidebar-list-linked">
        <a href="/dashboard/administration/settings/" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/') ?>">General</li>
        </a>
        <a href="/dashboard/administration/settings/products/" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/products/') ?>">Products</li>
        </a>
        <a href="/dashboard/administration/settings/paymentgateways/" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/paymentgateways/') ?>">Payment Gateways</li>
        </a>
        <a href="/dashboard/administration/settings/logs/" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/logs/') ?>">Logs</li>
        </a>
        <a href="/dashboard/administration/settings/ipBaning" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/ipBaning/') ?>">IP Banning</li>
        </a>
        <a href="/licensing/" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/licensing') ?>">Licensing</li>
        </a>
        <a href="/dashboard/administration/settings/update" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/update/') ?>">Updates</li>
        </a>
        <a href="/dashboard/administration/settings/about" class="sidebar-link-a">
            <li class="sidebar-link <?= isActive('/dashboard/administration/settings/about/') ?>">About Nexure Panel</li>
        </a>
    </ul>
</aside>