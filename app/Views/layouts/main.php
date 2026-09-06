<!DOCTYPE html>
<html lang="<?= $lang->getLocale() ?>" dir="<?= $lang->isRtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?= Security::e($title ?? $settings['meta_title'] ?? 'TrackXa') ?></title>
<meta name="description" content="<?= Security::e($description ?? $settings['meta_description'] ?? '') ?>">
<meta name="keywords"    content="<?= Security::e($keywords    ?? $settings['meta_keywords']    ?? '') ?>">
<meta name="robots"      content="index, follow">
<link rel="canonical"    href="<?= BASE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= Security::e($title ?? $settings['meta_title'] ?? 'TrackXa') ?>">
<meta property="og:url" content="<?= BASE_URL . $_SERVER['REQUEST_URI'] ?>">
<meta property="og:site_name" content="<?= Security::e($settings['site_name'] ?? 'TrackXa') ?>">
<meta name="twitter:card" content="summary_large_image">
<?php if (!empty($settings['site_favicon'])): ?>
<link rel="icon" href="<?= ASSETS_URL.'/'.$settings['site_favicon'] ?>" type="image/x-icon">
<?php else: ?>
<link rel="icon" href="<?= ASSETS_URL ?>/images/favicon.png" type="image/png">
<?php endif; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
<?php if ($lang->isRtl()): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
<?php endif; ?>
<style>
html,body{margin:0!important;padding:0!important;overflow-x:hidden}
nav.tx-navbar{margin-bottom:0!important}
.tx-hero{margin-top:0!important}
</style>
<script>var BASE_URL='<?= BASE_URL ?>';</script>
</head>
<body>

<!-- Loader -->
<div id="tx-loader"><div class="spinner"></div></div>

<!-- Drawer overlay (masqué par défaut) -->
<div id="tx-drawer-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:2040;backdrop-filter:blur(3px)"></div>

<!-- Mobile Drawer (hors écran par défaut) -->
<div id="tx-drawer" style="position:fixed;top:0;right:-320px;width:300px;max-width:85vw;height:100vh;z-index:2050;background:linear-gradient(170deg,#0f2647,#1a3c6e);display:flex;flex-direction:column;transition:right .3s ease;overflow-y:auto">

  <!-- Header drawer -->
  <div style="display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.3rem;border-bottom:1px solid rgba(255,255,255,.1);flex-shrink:0">
    <a href="<?= BASE_URL ?>">
      <?php if (!empty($settings['site_logo'])): ?>
        <img src="<?= ASSETS_URL.'/'.$settings['site_logo'] ?>" style="height:44px;width:auto" alt="">
      <?php else: ?>
        <span style="color:#fff;font-weight:800;font-size:1.2rem">Track<span style="color:#e8a020">Xa</span></span>
      <?php endif; ?>
    </a>
    <button id="tx-drawer-close" style="background:rgba(255,255,255,.1);border:none;color:#fff;width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:.95rem;display:flex;align-items:center;justify-content:center">
      <i class="fas fa-times"></i>
    </button>
  </div>

  <!-- Nav links -->
  <nav style="flex:1;padding:1rem">
    <a href="<?= BASE_URL ?>" style="display:flex;align-items:center;gap:.9rem;color:rgba(255,255,255,.85);padding:.8rem 1rem;border-radius:10px;text-decoration:none;font-size:.95rem;font-weight:500;margin-bottom:.2rem;transition:background .2s" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background=''">
      <i class="fas fa-home" style="color:#e8a020;width:20px;text-align:center"></i><?= $lang->get('home') ?>
    </a>
    <a href="<?= BASE_URL ?>/track" style="display:flex;align-items:center;gap:.9rem;color:rgba(255,255,255,.85);padding:.8rem 1rem;border-radius:10px;text-decoration:none;font-size:.95rem;font-weight:500;margin-bottom:.2rem;transition:background .2s" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background=''">
      <i class="fas fa-search-location" style="color:#e8a020;width:20px;text-align:center"></i><?= $lang->get('track_btn') ?>
    </a>
    <a href="<?= BASE_URL ?>/blog" style="display:flex;align-items:center;gap:.9rem;color:rgba(255,255,255,.85);padding:.8rem 1rem;border-radius:10px;text-decoration:none;font-size:.95rem;font-weight:500;margin-bottom:.2rem;transition:background .2s" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background=''">
      <i class="fas fa-newspaper" style="color:#e8a020;width:20px;text-align:center"></i><?= $lang->get('blog') ?>
    </a>
    <a href="<?= BASE_URL ?>/faq" style="display:flex;align-items:center;gap:.9rem;color:rgba(255,255,255,.85);padding:.8rem 1rem;border-radius:10px;text-decoration:none;font-size:.95rem;font-weight:500;margin-bottom:.2rem;transition:background .2s" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background=''">
      <i class="fas fa-circle-question" style="color:#e8a020;width:20px;text-align:center"></i><?= $lang->get('faq') ?>
    </a>
    <a href="<?= BASE_URL ?>/contact" style="display:flex;align-items:center;gap:.9rem;color:rgba(255,255,255,.85);padding:.8rem 1rem;border-radius:10px;text-decoration:none;font-size:.95rem;font-weight:500;margin-bottom:.2rem;transition:background .2s" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background=''">
      <i class="fas fa-envelope" style="color:#e8a020;width:20px;text-align:center"></i><?= $lang->get('contact') ?>
    </a>
  </nav>

  <!-- Languages -->
  <div style="padding:1rem 1.3rem;border-top:1px solid rgba(255,255,255,.1)">
    <div style="font-size:.68rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.7rem">Language</div>
    <div style="display:flex;flex-wrap:wrap;gap:.4rem">
      <?php foreach (['en'=>'🇬🇧 EN','fr'=>'🇫🇷 FR','es'=>'🇪🇸 ES','de'=>'🇩🇪 DE','it'=>'🇮🇹 IT','pt'=>'🇵🇹 PT','ar'=>'🇸🇦 AR'] as $code => $label): ?>
      <a href="<?= BASE_URL ?>/lang/<?= $code ?>" style="background:<?= $lang->getLocale()===$code?'#e8a020':'rgba(255,255,255,.1)' ?>;color:#fff;padding:.25rem .6rem;border-radius:6px;font-size:.72rem;font-weight:600;text-decoration:none"><?= $label ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- CTA -->
  <div style="padding:1.2rem 1.3rem;flex-shrink:0">
    <a href="<?= BASE_URL ?>/track" style="display:flex;align-items:center;justify-content:center;gap:.5rem;background:#e8a020;color:#fff;border-radius:10px;padding:.85rem;font-weight:700;font-size:.95rem;text-decoration:none">
      <i class="fas fa-magnifying-glass-location"></i><?= $lang->get('track_btn') ?>
    </a>
  </div>

</div>

<!-- Navbar -->
<nav class="tx-navbar navbar navbar-expand-lg">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand" href="<?= BASE_URL ?>">
      <?php if (!empty($settings['site_logo'])): ?>
        <img src="<?= ASSETS_URL.'/'.$settings['site_logo'] ?>" alt="<?= Security::e($settings['site_name'] ?? 'TrackXa') ?>">
      <?php else: ?>
        <span style="color:#fff;font-weight:800;font-size:1.4rem">Track<span style="color:var(--tx-accent)">Xa</span></span>
      <?php endif; ?>
    </a>

    <!-- Hamburger mobile uniquement -->
    <button id="tx-hamburger" class="navbar-toggler border-0 d-lg-none" type="button" aria-label="Menu">
      <i class="fas fa-bars" style="color:#fff;font-size:1.15rem"></i>
    </button>

    <!-- Desktop nav -->
    <div class="collapse navbar-collapse d-none d-lg-flex align-items-center ms-auto gap-1">
      <a class="nav-link tx-navbar-link" href="<?= BASE_URL ?>"><i class="fas fa-home me-1"></i><?= $lang->get('home') ?></a>
      <a class="nav-link tx-navbar-link" href="<?= BASE_URL ?>/track"><i class="fas fa-search-location me-1"></i><?= $lang->get('track_btn') ?></a>
      <a class="nav-link tx-navbar-link" href="<?= BASE_URL ?>/blog"><i class="fas fa-newspaper me-1"></i><?= $lang->get('blog') ?></a>
      <a class="nav-link tx-navbar-link" href="<?= BASE_URL ?>/faq"><i class="fas fa-circle-question me-1"></i><?= $lang->get('faq') ?></a>
      <a class="nav-link tx-navbar-link" href="<?= BASE_URL ?>/contact"><i class="fas fa-envelope me-1"></i><?= $lang->get('contact') ?></a>
      <div class="dropdown ms-2">
        <button class="tx-lang-btn dropdown-toggle" data-bs-toggle="dropdown">
          <i class="fas fa-globe me-1"></i><?= strtoupper($lang->getLocale()) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <?php foreach (['en'=>'🇬🇧 English','fr'=>'🇫🇷 Français','es'=>'🇪🇸 Español','de'=>'🇩🇪 Deutsch','it'=>'🇮🇹 Italiano','pt'=>'🇵🇹 Português','ar'=>'🇸🇦 العربية'] as $code => $label): ?>
          <li><a class="dropdown-item <?= $lang->getLocale()===$code?'active':'' ?>" href="<?= BASE_URL ?>/lang/<?= $code ?>"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <a href="<?= BASE_URL ?>/track" class="tx-btn-primary ms-2">
        <i class="fas fa-magnifying-glass-location"></i> <?= $lang->get('track_btn') ?>
      </a>
    </div>

  </div>
</nav>

<!-- Main Content -->
<?= $content ?>

<!-- Footer -->
<footer class="tx-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <h5><?= Security::e($settings['site_name'] ?? 'TrackXa') ?></h5>
        <p style="font-size:.9rem;color:rgba(255,255,255,.6);line-height:1.7"><?= Security::e($settings['site_tagline'] ?? 'Professional Shipment Tracking Platform') ?></p>
        <div class="tx-social-links">
          <?php if (!empty($settings['facebook_url'])): ?><a href="<?= $settings['facebook_url'] ?>" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
          <?php if (!empty($settings['twitter_url'])): ?><a href="<?= $settings['twitter_url'] ?>" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a><?php endif; ?>
          <?php if (!empty($settings['instagram_url'])): ?><a href="<?= $settings['instagram_url'] ?>" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a><?php endif; ?>
          <?php if (!empty($settings['linkedin_url'])): ?><a href="<?= $settings['linkedin_url'] ?>" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <h5>Quick Links</h5>
        <a href="<?= BASE_URL ?>"><i class="fas fa-angle-right me-1 text-warning"></i><?= $lang->get('home') ?></a>
        <a href="<?= BASE_URL ?>/track"><i class="fas fa-angle-right me-1 text-warning"></i><?= $lang->get('track_btn') ?></a>
        <a href="<?= BASE_URL ?>/blog"><i class="fas fa-angle-right me-1 text-warning"></i><?= $lang->get('blog') ?></a>
        <a href="<?= BASE_URL ?>/faq"><i class="fas fa-angle-right me-1 text-warning"></i>FAQ</a>
        <a href="<?= BASE_URL ?>/contact"><i class="fas fa-angle-right me-1 text-warning"></i><?= $lang->get('contact') ?></a>
      </div>
      <div class="col-lg-3 col-md-3 col-6">
        <h5>Services</h5>
        <a href="#"><i class="fas fa-angle-right me-1 text-warning"></i>Express Shipping</a>
        <a href="#"><i class="fas fa-angle-right me-1 text-warning"></i>International</a>
        <a href="#"><i class="fas fa-angle-right me-1 text-warning"></i>API Integration</a>
        <a href="#"><i class="fas fa-angle-right me-1 text-warning"></i>E-commerce</a>
      </div>
      <div class="col-lg-3 col-md-6">
        <h5><?= $lang->get('contact_us') ?></h5>
        <?php if (!empty($settings['site_address'])): ?><p style="font-size:.85rem;color:rgba(255,255,255,.6)"><i class="fas fa-map-marker-alt me-2 text-warning"></i><?= Security::e($settings['site_address']) ?></p><?php endif; ?>
        <?php if (!empty($settings['site_phone'])): ?><p style="font-size:.85rem;color:rgba(255,255,255,.6)"><i class="fas fa-phone me-2 text-warning"></i><?= Security::e($settings['site_phone']) ?></p><?php endif; ?>
        <?php if (!empty($settings['site_email'])): ?><p style="font-size:.85rem;color:rgba(255,255,255,.6)"><i class="fas fa-envelope me-2 text-warning"></i><?= Security::e($settings['site_email']) ?></p><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="tx-footer-bottom">
    <div class="container">
      &copy; <?= date('Y') ?> <?= Security::e($settings['site_name'] ?? 'TrackXa') ?>. <?= $lang->get('all_rights_reserved') ?>.
      &nbsp;|&nbsp;<a href="#" style="color:rgba(255,255,255,.4)"><?= $lang->get('privacy_policy') ?></a>
      &nbsp;|&nbsp;<a href="#" style="color:rgba(255,255,255,.4)"><?= $lang->get('terms_of_service') ?></a>
    </div>
  </div>
</footer>

<?php if (!empty($settings['whatsapp_number'])): ?>
<a href="https://wa.me/<?= preg_replace('/\D/','',$settings['whatsapp_number']) ?>" class="tx-whatsapp-btn" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
<?php endif; ?>
<button id="tx-backtop" title="Back to top"><i class="fas fa-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= ASSETS_URL ?>/js/main.js"></script>
<?php if (!empty($settings['google_analytics'])): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= Security::e($settings['google_analytics']) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= Security::e($settings['google_analytics']) ?>');</script>
<?php endif; ?>

<script>
// Drawer latéral mobile
(function(){
  var drawer  = document.getElementById('tx-drawer');
  var overlay = document.getElementById('tx-drawer-overlay');
  var burger  = document.getElementById('tx-hamburger');
  var closeBtn= document.getElementById('tx-drawer-close');
  if (!drawer || !burger) return;
  function open(){
    drawer.style.right = '0';
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
  function close(){
    drawer.style.right = '-320px';
    overlay.style.display = 'none';
    document.body.style.overflow = '';
  }
  burger.addEventListener('click', open);
  if (closeBtn) closeBtn.addEventListener('click', close);
  overlay.addEventListener('click', close);
  // Fermer au clic sur un lien
  drawer.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', close); });
})();
</script>
</body>
</html>
