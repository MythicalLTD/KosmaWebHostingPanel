<?php


function is_active_page($page_urls)
{
  foreach ($page_urls as $page_url) {
    if (strpos($_SERVER['REQUEST_URI'], $page_url) !== false) {
      return true;
    }
  }
  return false;
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="/" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold">
        <?= $settingsManager->getSetting('name') ?>
      </span>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Home</span>
    </li>
    <li class="menu-item <?php echo is_active_page(['/']) ? 'active' : ''; ?>">
      <a href="/" class="menu-link">
        <i class="menu-icon tf-icons ti ti-home"></i>
        <div>Dashboard</div>
      </a>
    </li>
    <?php
    if ($sessionManager->getUserInfo("role") == "administrator") {
      ?>
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Administration Tools</span>
      </li>
      <li class="menu-item <?php echo is_active_page(['/admin/api']) ? 'active' : ''; ?>">
        <a href="/admin/api" class="menu-link">
          <i class="menu-icon tf-icons ti ti-device-gamepad-2"></i>
          <div>Application API</div>
        </a>
      </li>
      <li class="menu-item <?php echo is_active_page(['/admin/users/view', '/admin/users/edit', '/admin/users/new']) ? 'active' : ''; ?>">
        <a href="/admin/users/view" class="menu-link">
          <i class="menu-icon tf-icons ti ti-users"></i>
          <div>Users</div>
        </a>
      </li>
      <li class="menu-item <?php echo is_active_page(['/admin/settings']) ? 'active' : ''; ?>">
        <a href="/admin/settings" class="menu-link">
          <i class="menu-icon tf-icons ti ti-settings"></i>
          <div>Settings</div>
        </a>
      </li>
      <?php
    }
    ?>
  </ul>
</aside>