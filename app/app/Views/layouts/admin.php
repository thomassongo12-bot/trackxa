<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= Security::e($title ?? 'Admin – TrackXa') ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
<script>var BASE_URL='<?= BASE_URL ?>';</script>
</head>
<body class="admin-body">
<div id="adm-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1039;" onclick="this.style.display='none';document.querySelector('.adm-sidebar').classList.remove('open');"></div>

<div class="adm-wrapper">
  <!-- Sidebar -->
  <aside class="adm-sidebar">
    <div class="adm-sidebar-brand">
      <span>Track<em>Xa</em></span>
    </div>

    <?php
    $uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $adminBase  = BASE_URL . '/admin';
    function isActive(string $path, string $uri): string {
        return str_contains($uri, $path) ? 'active' : '';
    }
    ?>

    <nav style="flex:1;padding:.5rem 0;">
      <div class="adm-nav-section">
        <span class="adm-nav-label">Main</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/dashboard" class="adm-nav-link <?= isActive('/admin/dashboard',$uri) ?>">
              <span class="icon"><i class="fas fa-chart-pie"></i></span> Dashboard
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Shipments</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/shipments" class="adm-nav-link <?= isActive('/admin/shipments',$uri) ?>">
              <span class="icon"><i class="fas fa-boxes-stacked"></i></span> All Shipments
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/shipments/create" class="adm-nav-link">
              <span class="icon"><i class="fas fa-plus-circle"></i></span> Create Shipment
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Content</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/blog" class="adm-nav-link <?= isActive('/admin/blog',$uri) ?>">
              <span class="icon"><i class="fas fa-newspaper"></i></span> Blog
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/faq" class="adm-nav-link <?= isActive('/admin/faq',$uri) ?>">
              <span class="icon"><i class="fas fa-circle-question"></i></span> FAQ
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/contacts" class="adm-nav-link <?= isActive('/admin/contacts',$uri) ?>">
              <span class="icon"><i class="fas fa-envelope"></i></span> Messages
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Integration</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/websites" class="adm-nav-link <?= isActive('/admin/websites',$uri) ?>">
              <span class="icon"><i class="fas fa-globe"></i></span> Websites
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/api-keys" class="adm-nav-link <?= isActive('/admin/api-keys',$uri) ?>">
              <span class="icon"><i class="fas fa-key"></i></span> API Keys
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/api-logs" class="adm-nav-link <?= isActive('/admin/api-logs',$uri) ?>">
              <span class="icon"><i class="fas fa-scroll"></i></span> API Logs
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/api/docs" class="adm-nav-link" target="_blank">
              <span class="icon"><i class="fas fa-book"></i></span> API Docs
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Configuration</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/carriers" class="adm-nav-link <?= isActive('/admin/carriers',$uri) ?>">
              <span class="icon"><i class="fas fa-truck"></i></span> Carriers
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/countries" class="adm-nav-link <?= isActive('/admin/countries',$uri) ?>">
              <span class="icon"><i class="fas fa-earth-africa"></i></span> Countries
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/partners" class="adm-nav-link <?= isActive('/admin/partners',$uri) ?>">
              <span class="icon"><i class="fas fa-handshake"></i></span> Partners
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/logs" class="adm-nav-link <?= isActive('/admin/logs',$uri) ?>">
              <span class="icon"><i class="fas fa-list-check"></i></span> Activity Logs
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/settings" class="adm-nav-link <?= isActive('/admin/settings',$uri) ?>">
              <span class="icon"><i class="fas fa-gear"></i></span> Settings
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="adm-sidebar-footer">
      <div class="adm-user-mini">
        <img src="<?= !empty($session->get('admin_avatar')) ? ASSETS_URL.'/'.$session->get('admin_avatar') : 'https://ui-avatars.com/api/?name='.urlencode($session->get('admin_name','A')).'&background=e8a020&color=fff&size=80' ?>" alt="Avatar">
        <div class="info">
          <div class="name"><?= Security::e($session->get('admin_name','Admin')) ?></div>
          <div class="role"><?= Security::e($session->get('admin_role','operator')) ?></div>
        </div>
      </div>
      <a href="<?= BASE_URL ?>/admin/logout" style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.5);font-size:.8rem;padding:.5rem;margin-top:.5rem;border-radius:8px;transition:all .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.5)'">
        <i class="fas fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="adm-main">
    <div class="adm-topbar">
      <div class="adm-topbar-left">
        <button class="adm-menu-toggle"><i class="fas fa-bars"></i></button>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 adm-breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item active"><?= Security::e(explode(' – ', $title ?? 'Dashboard')[0]) ?></li>
          </ol>
        </nav>
      </div>
      <div class="adm-topbar-right">
        <a href="<?= BASE_URL ?>" target="_blank" class="adm-topbar-btn" title="View site"><i class="fas fa-external-link-alt"></i></a>
        <a href="<?= BASE_URL ?>/admin/profile" class="adm-topbar-btn" title="Profile"><i class="fas fa-user"></i></a>
        <a href="<?= BASE_URL ?>/admin/profile">
          <img src="<?= !empty($session->get('admin_avatar')) ? ASSETS_URL.'/'.$session->get('admin_avatar') : 'https://ui-avatars.com/api/?name='.urlencode($session->get('admin_name','A')).'&background=1a3c6e&color=fff&size=80' ?>" class="adm-topbar-avatar" alt="Avatar">
        </a>
      </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($flash = $session->getFlash('success')): ?>
    <div class="adm-flash alert alert-success alert-dismissible mx-3 mt-3 mb-0" role="alert">
      <i class="fas fa-check-circle me-2"></i><?= Security::e($flash) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if ($flash = $session->getFlash('error')): ?>
    <div class="adm-flash alert alert-danger alert-dismissible mx-3 mt-3 mb-0" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i><?= Security::e($flash) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="adm-content">
      <?= $content ?>
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="<?= ASSETS_URL ?>/js/admin.js"></script>
</body>
</html>
