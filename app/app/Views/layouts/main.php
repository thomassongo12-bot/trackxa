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

<!-- Open Graph -->
<meta property="og:type"        content="website">
<meta property="og:title"       content="<?= Security::e($title ?? $settings['meta_title'] ?? 'TrackXa') ?>">
<meta property="og:description" content="<?= Security::e($description ?? $settings['meta_description'] ?? '') ?>">
<meta property="og:url"         content="<?= BASE_URL . $_SERVER['REQUEST_URI'] ?>">
<meta property="og:image"       content="<?= !empty($ogImage) ? ASSETS_URL.'/'.$ogImage : ASSETS_URL.'/images/og-default.jpg' ?>">
<meta property="og:site_name"   content="<?= Security::e($settings['site_name'] ?? 'TrackXa') ?>">
<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= Security::e($title ?? '') ?>">
<meta name="twitter:description" content="<?= Security::e($description ?? '') ?>">
<!-- Schema.org -->
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","name":"<?= Security::e($settings['site_name'] ?? 'TrackXa') ?>","url":"<?= BASE_URL ?>","potentialAction":{"@type":"SearchAction","target":"<?= BASE_URL ?>/track/{search_term_string}","query-input":"required name=search_term_string"}}</script>
<!-- Favicon -->
<?php if (!empty($settings['site_favicon'])): ?>
<link rel="icon" href="<?= ASSETS_URL.'/'.$settings['site_favicon'] ?>" type="image/x-icon">
<?php else: ?>
<link rel="icon" href="<?= ASSETS_URL ?>/images/favicon.png" type="image/png">
<?php endif; ?>
<!-- Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- TrackXa CSS -->
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
<?php if ($lang->isRtl()): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
<?php endif; ?>
<script>var BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<!-- Page Loader -->
<div id="tx-loader"><div class="spinner"></div></div>

<!-- Navbar -->
<nav class="tx-navbar navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>">
      <?php if (!empty($settings['site_logo'])): ?>
        <img src="<?= ASSETS_URL.'/'.$settings['site_logo'] ?>" alt="<?= Security::e($settings['site_name'] ?? 'TrackXa') ?>">
      <?php else: ?>
        <span style="color:#fff;font-weight:800;font-size:1.4rem;">Track<span style="color:var(--tx-accent)">Xa</span></span>
      <?php endif; ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#txNav" aria-label="Menu">
      <i class="fas fa-bars" style="color:#fff;font-size:1.2rem;"></i>
    </button>
    <div class="collapse navbar-collapse" id="txNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>"><i class="fas fa-home me-1"></i><?= $lang->get('home') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/track"><i class="fas fa-search-location me-1"></i><?= $lang->get('track_btn') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/blog"><i class="fas fa-newspaper me-1"></i><?= $lang->get('blog') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/faq"><i class="fas fa-circle-question me-1"></i><?= $lang->get('faq') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/contact"><i class="fas fa-envelope me-1"></i><?= $lang->get('contact') ?></a></li>
        <!-- Language Switcher -->
        <li class="nav-item dropdown lang-switcher ms-2">
          <button class="dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-globe me-1"></i><?= strtoupper($lang->getLocale()) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <?php foreach (['en'=>'🇬🇧 English','fr'=>'🇫🇷 Français','es'=>'🇪🇸 Español','de'=>'🇩🇪 Deutsch','it'=>'🇮🇹 Italiano','pt'=>'🇵🇹 Português','ar'=>'🇸🇦 العربية'] as $code => $label): ?>
            <li><a class="dropdown-item <?= $lang->getLocale()===$code?'active':'' ?>" href="<?= BASE_URL ?>/lang/<?= $code ?>"><?= $label ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item ms-2">
          <a href="<?= BASE_URL ?>/track" class="tx-btn-primary">
            <i class="fas fa-magnifying-glass-location"></i> <?= $lang->get('track_btn') ?>
          </a>
        </li>
      </ul>
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
        <p style="font-size:.9rem;color:rgba(255,255,255,.6);line-height:1.7;"><?= Security::e($settings['site_tagline'] ?? 'Professional Shipment Tracking Platform') ?></p>
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
        <a href="<?= BASE_URL ?>/api/docs"><i class="fas fa-angle-right me-1 text-warning"></i>API Docs</a>
      </div>
      <div class="col-lg-3 col-md-6">
        <h5><?= $lang->get('contact_us') ?></h5>
        <?php if (!empty($settings['site_address'])): ?>
        <p style="font-size:.85rem;color:rgba(255,255,255,.6)"><i class="fas fa-map-marker-alt me-2 text-warning"></i><?= Security::e($settings['site_address']) ?></p>
        <?php endif; ?>
        <?php if (!empty($settings['site_phone'])): ?>
        <p style="font-size:.85rem;color:rgba(255,255,255,.6)"><i class="fas fa-phone me-2 text-warning"></i><?= Security::e($settings['site_phone']) ?></p>
        <?php endif; ?>
        <?php if (!empty($settings['site_email'])): ?>
        <p style="font-size:.85rem;color:rgba(255,255,255,.6)"><i class="fas fa-envelope me-2 text-warning"></i><?= Security::e($settings['site_email']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="tx-footer-bottom">
    <div class="container">
      &copy; <?= date('Y') ?> <?= Security::e($settings['site_name'] ?? 'TrackXa') ?>. <?= $lang->get('all_rights_reserved') ?>.
      &nbsp;|&nbsp; <a href="#" style="color:rgba(255,255,255,.4)"><?= $lang->get('privacy_policy') ?></a>
      &nbsp;|&nbsp; <a href="#" style="color:rgba(255,255,255,.4)"><?= $lang->get('terms_of_service') ?></a>
    </div>
  </div>
</footer>

<!-- WhatsApp Button -->
<?php if (!empty($settings['whatsapp_number'])): ?>
<a href="https://wa.me/<?= preg_replace('/\D/','',$settings['whatsapp_number']) ?>" class="tx-whatsapp-btn" target="_blank" title="WhatsApp">
  <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Back to Top -->
<button id="tx-backtop" title="Back to top"><i class="fas fa-arrow-up"></i></button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- TrackXa JS -->
<script src="<?= ASSETS_URL ?>/js/main.js"></script>
<?php if (!empty($settings['google_analytics'])): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= Security::e($settings['google_analytics']) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= Security::e($settings['google_analytics']) ?>');</script>
<?php endif; ?>
</body>
</html>
