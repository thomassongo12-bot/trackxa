<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= Security::e($title ?? 'Admin â€“ TrackXa') ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
<script>var BASE_URL='<?= BASE_URL ?>';</script>
</head>
<body class="admin-body">
<div id="adm-overlay" onclick="document.querySelector('.adm-sidebar').classList.remove('open');this.classList.remove('active');"></div>

<div class="adm-wrapper">
  <!-- Sidebar -->
  <aside class="adm-sidebar">
    <div class="adm-sidebar-brand">
      <span>Track<em>Xa</em></span>
    </div>

    <?php
    $uri       = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $al        = $adminLang ?? AdminLang::getInstance();
    // Retire le sous-dossier XAMPP (ex: /trackxa) de l'URI pour comparaisons propres
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');
    $cleanUri  = ($scriptDir && $scriptDir !== '/')
                 ? preg_replace('#^'.preg_quote($scriptDir,'#').'#', '', $uri)
                 : $uri;
    $cleanUri  = $cleanUri ?: '/';

    function isActive(string $path, string $cleanUri, bool $exact = false): string {
        if ($exact) return $cleanUri === $path ? 'active' : '';
        if ($cleanUri === $path) return 'active';
        if (str_starts_with($cleanUri, rtrim($path,'/') . '/')) return 'active';
        if (str_starts_with($cleanUri, rtrim($path,'/') . '?')) return 'active';
        return '';
    }
    ?>

    <nav style="flex:1;padding:.5rem 0;">
      <div class="adm-nav-section">
        <span class="adm-nav-label"><?= $al->get('dashboard') ?></span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/dashboard" class="adm-nav-link <?= isActive('/admin/dashboard',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-chart-pie"></i></span> <?= $al->get('dashboard') ?>
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label"><?= $al->get('shipments') ?></span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <?php $shipActive = (isActive('/admin/shipments',$cleanUri) && $cleanUri !== '/admin/shipments/create') ? 'active' : ''; ?>
            <a href="<?= BASE_URL ?>/admin/shipments" class="adm-nav-link <?= $shipActive ?>">
              <span class="icon"><i class="fas fa-boxes-stacked"></i></span> <?= $al->get('all_shipments') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/shipments/create" class="adm-nav-link <?= isActive('/admin/shipments/create',$cleanUri,true) ?>">
              <span class="icon"><i class="fas fa-plus-circle"></i></span> <?= $al->get('create_shipment') ?>
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Content</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/blog" class="adm-nav-link <?= isActive('/admin/blog',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-newspaper"></i></span> <?= $al->get('blog') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/faq" class="adm-nav-link <?= isActive('/admin/faq',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-circle-question"></i></span> <?= $al->get('faq') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/contacts" class="adm-nav-link <?= isActive('/admin/contacts',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-envelope"></i></span> <?= $al->get('messages') ?>
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Integration</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/websites" class="adm-nav-link <?= isActive('/admin/websites',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-globe"></i></span> <?= $al->get('websites') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/api-keys" class="adm-nav-link <?= isActive('/admin/api-keys',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-key"></i></span> <?= $al->get('api_keys') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/api-logs" class="adm-nav-link <?= isActive('/admin/api-logs',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-scroll"></i></span> <?= $al->get('api_logs') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/api/docs" class="adm-nav-link" target="_blank">
              <span class="icon"><i class="fas fa-book"></i></span> <?= $al->get('api_docs') ?>
            </a>
          </li>
        </ul>
      </div>

      <div class="adm-nav-section">
        <span class="adm-nav-label">Configuration</span>
        <ul style="list-style:none;padding:0;margin:.4rem 0;">
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/carriers" class="adm-nav-link <?= isActive('/admin/carriers',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-truck"></i></span> <?= $al->get('carriers') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/countries" class="adm-nav-link <?= isActive('/admin/countries',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-earth-africa"></i></span> <?= $al->get('countries') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/partners" class="adm-nav-link <?= isActive('/admin/partners',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-handshake"></i></span> <?= $al->get('partners') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/logs" class="adm-nav-link <?= isActive('/admin/logs',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-list-check"></i></span> <?= $al->get('activity_logs') ?>
            </a>
          </li>
          <li class="adm-nav-item">
            <a href="<?= BASE_URL ?>/admin/settings" class="adm-nav-link <?= isActive('/admin/settings',$cleanUri) ?>">
              <span class="icon"><i class="fas fa-gear"></i></span> <?= $al->get('settings') ?>
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
        <i class="fas fa-right-from-bracket"></i> <?= isset($al) ? $al->get('logout') : 'Logout' ?>
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
            <li class="breadcrumb-item active"><?= Security::e(explode(' â€“ ', $title ?? 'Dashboard')[0]) ?></li>
          </ol>
        </nav>
      </div>
      <div class="adm-topbar-right">
        <!-- Admin Language Switcher -->
        <div class="dropdown">
          <button class="adm-topbar-btn dropdown-toggle" data-bs-toggle="dropdown" title="Language" style="gap:.3rem;min-width:70px;font-size:.8rem;font-weight:600">
            <?php $al = $adminLang ?? AdminLang::getInstance(); ?>
            <?= $al->getLocale() === 'fr' ? 'ðŸ‡«ðŸ‡· FR' : 'ðŸ‡¬ðŸ‡§ EN' ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" style="min-width:120px">
            <li><a class="dropdown-item <?= ($al->getLocale()==='en')?'active':'' ?>" href="<?= BASE_URL ?>/admin/lang/en">ðŸ‡¬ðŸ‡§ English</a></li>
            <li><a class="dropdown-item <?= ($al->getLocale()==='fr')?'active':'' ?>" href="<?= BASE_URL ?>/admin/lang/fr">ðŸ‡«ðŸ‡· FranÃ§ais</a></li>
          </ul>
        </div>
        <a href="<?= BASE_URL ?>" target="_blank" class="adm-topbar-btn" title="<?= $al->get('view_site') ?>"><i class="fas fa-external-link-alt"></i></a>
        <a href="<?= BASE_URL ?>/admin/profile" class="adm-topbar-btn" title="<?= $al->get('profile') ?>"><i class="fas fa-user"></i></a>
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

