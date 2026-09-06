<?php
$siteName = Security::e($settings['site_name'] ?? 'TrackXa');
$statuses = ShipmentModel::STATUSES;
?>
<!-- ── HERO ─────────────────────────────────────────────────── -->
<section class="tx-hero">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 tx-hero-content">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="badge" style="background:rgba(232,160,32,.2);color:var(--tx-accent);font-size:.8rem;padding:.4rem .9rem;border-radius:100px;border:1px solid rgba(232,160,32,.3);">
            <i class="fas fa-bolt me-1"></i> Real-Time Tracking
          </span>
        </div>
        <h1>Track Your <span>Shipments</span><br>Anywhere, Anytime</h1>
        <p class="lead mt-3 mb-4">Professional shipment tracking platform. Monitor your packages in real-time with accurate updates from dispatch to delivery.</p>
        <div class="tx-hero-stats">
          <div class="tx-hero-stat">
            <span class="number" data-counter data-target="<?= (int)($stats['total'] ?? 0) ?>" data-suffix="+"><?= number_format((int)($stats['total'] ?? 0)) ?>+</span>
            <span class="label">Shipments Tracked</span>
          </div>
          <div class="tx-hero-stat">
            <span class="number" data-counter data-target="<?= (int)($countryCount ?? 35) ?>" data-suffix="+"><?= (int)($countryCount ?? 35) ?>+</span>
            <span class="label">Countries</span>
          </div>
          <div class="tx-hero-stat">
            <span class="number" data-counter data-target="99" data-suffix="%">99%</span>
            <span class="label">Accuracy</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="tx-track-box">
          <h2><i class="fas fa-magnifying-glass-location me-2"></i><?= $lang->get('track_your_shipment') ?></h2>
          <form action="<?= BASE_URL ?>/track" method="POST" id="tx-track-form">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <div class="mb-3">
              <input type="text" name="tracking_number" id="tracking_number" class="tx-track-input" placeholder="<?= $lang->get('enter_tracking') ?>" autocomplete="off" required>
            </div>
            <button type="submit" class="tx-track-btn w-100">
              <i class="fas fa-search me-2"></i><?= $lang->get('track_btn') ?>
            </button>
          </form>
          <p class="mt-3 mb-0" style="color:rgba(255,255,255,.5);font-size:.78rem;text-align:center;">
            <i class="fas fa-shield-check me-1 text-warning"></i> Supports: Tracking Number, Reference Number, Order Number
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── SERVICES ──────────────────────────────────────────────── -->
<section class="tx-section" id="services">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category"><?= $lang->get('our_services') ?></span>
      <h2 class="tx-section-title mt-2"><?= $lang->get('our_services') ?></h2>
      <div class="tx-divider"></div>
      <p class="tx-section-subtitle mx-auto">End-to-end shipment visibility with enterprise-grade reliability</p>
    </div>
    <div class="row g-4">
      <?php $services = [
        ['icon'=>'fa-plane','title'=>'Express Air Freight','desc'=>'Priority shipping by air with 1-3 day delivery worldwide. Real-time flight tracking included.'],
        ['icon'=>'fa-ship','title'=>'Ocean Freight','desc'=>'Cost-effective sea shipping for large cargo. Full container and LCL options available.'],
        ['icon'=>'fa-truck-fast','title'=>'Last Mile Delivery','desc'=>'Efficient last-mile logistics ensuring packages reach their final destination on time.'],
        ['icon'=>'fa-warehouse','title'=>'Warehouse Management','desc'=>'Secure warehousing with inventory management and fulfillment services.'],
        ['icon'=>'fa-file-shield','title'=>'Customs Clearance','desc'=>'Expert customs documentation and clearance for smooth international shipments.'],
        ['icon'=>'fa-code','title'=>'API Integration','desc'=>'Seamlessly integrate TrackXa with your e-commerce platform via our REST API.'],
      ]; foreach ($services as $s): ?>
      <div class="col-lg-4 col-md-6">
        <div class="tx-card h-100 p-4">
          <div class="tx-service-icon"><i class="fas <?= $s['icon'] ?>"></i></div>
          <h5 style="font-weight:700;color:var(--tx-primary)"><?= $s['title'] ?></h5>
          <p style="color:#64748b;font-size:.9rem;margin:0"><?= $s['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── WHY CHOOSE US ─────────────────────────────────────────── -->
<section class="tx-section tx-section-dark" id="why-us">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="tx-blog-category"><?= $lang->get('why_choose_us') ?></span>
        <h2 class="tx-section-title mt-2">Why Businesses Trust TrackXa</h2>
        <div class="tx-divider" style="margin:1rem 0;"></div>
        <?php $features = [
          ['icon'=>'fa-bolt','title'=>'Real-Time Updates','desc'=>'Instant status notifications as your shipment moves through every checkpoint.'],
          ['icon'=>'fa-lock','title'=>'Secure & Reliable','desc'=>'Enterprise-grade security with 99.9% uptime guarantee.'],
          ['icon'=>'fa-globe','title'=>'Global Coverage','desc'=>'Track shipments across 35+ countries with our worldwide carrier network.'],
          ['icon'=>'fa-plug','title'=>'Easy API','desc'=>'Integrate in minutes. Connect your store and automate shipment creation.'],
        ]; foreach ($features as $f): ?>
        <div class="d-flex gap-3 mb-3 p-3 bg-white rounded-3 shadow-sm">
          <div class="tx-feature-icon flex-shrink-0"><i class="fas <?= $f['icon'] ?>"></i></div>
          <div>
            <h6 style="font-weight:700;color:var(--tx-primary);margin:0"><?= $f['title'] ?></h6>
            <p style="color:#64748b;font-size:.88rem;margin:.3rem 0 0"><?= $f['desc'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6">
        <div class="position-relative">
          <div style="background:linear-gradient(135deg,var(--tx-primary),#2563eb);border-radius:24px;padding:2.5rem;color:#fff;">
            <h4 style="font-weight:800;margin-bottom:1.5rem;"><i class="fas fa-chart-line me-2 text-warning"></i>Live Statistics</h4>
            <?php $statItems = [
              ['label'=>'Total Shipments','value'=>number_format((int)($stats['total']??0)),'icon'=>'fa-box'],
              ['label'=>'Delivered Today','value'=>number_format((int)($stats['today']??0)),'icon'=>'fa-circle-check'],
              ['label'=>'In Transit','value'=>number_format((int)($stats['in_transit']??0)),'icon'=>'fa-truck'],
              ['label'=>'Success Rate','value'=>'99.2%','icon'=>'fa-star'],
            ]; foreach ($statItems as $item): ?>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(255,255,255,.1)">
              <div class="d-flex align-items-center gap-2">
                <i class="fas <?= $item['icon'] ?> text-warning"></i>
                <span style="font-size:.9rem;color:rgba(255,255,255,.8)"><?= $item['label'] ?></span>
              </div>
              <span style="font-weight:800;font-size:1.1rem;color:#fff"><?= $item['value'] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── HOW IT WORKS ──────────────────────────────────────────── -->
<section class="tx-section" id="how-it-works">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category"><?= $lang->get('how_it_works') ?></span>
      <h2 class="tx-section-title mt-2"><?= $lang->get('how_it_works') ?></h2>
      <div class="tx-divider"></div>
    </div>
    <div class="row g-4">
      <?php $steps = [
        ['num'=>'1','icon'=>'fa-box','title'=>'Shipment Created','desc'=>'Merchant creates shipment via API or admin panel. Unique tracking number generated instantly.'],
        ['num'=>'2','icon'=>'fa-qrcode','title'=>'Get Tracking Link','desc'=>'Share the tracking URL with your customer. They can monitor every update in real time.'],
        ['num'=>'3','icon'=>'fa-route','title'=>'Track in Transit','desc'=>'Every scan, location update and status change appears on the timeline instantly.'],
        ['num'=>'4','icon'=>'fa-circle-check','title'=>'Delivered!','desc'=>'Customer receives delivery confirmation with timestamp and proof of delivery.'],
      ]; foreach ($steps as $i => $s): ?>
      <div class="col-lg-3 col-md-6">
        <div class="tx-step text-center px-2">
          <?php if ($i < count($steps)-1): ?>
          <div class="tx-step-connector"></div>
          <?php endif; ?>
          <div class="tx-step-number"><?= $s['num'] ?></div>
          <div class="mb-3" style="font-size:2rem;color:var(--tx-primary)"><i class="fas <?= $s['icon'] ?>"></i></div>
          <h6 style="font-weight:700;color:var(--tx-primary)"><?= $s['title'] ?></h6>
          <p style="font-size:.88rem;color:#64748b;margin:0"><?= $s['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── STATS ─────────────────────────────────────────────────── -->
<section class="tx-stats-section">
  <div class="container">
    <div class="row g-0">
      <?php $statsData = [
        ['icon'=>'fa-boxes-stacked','value'=>number_format((int)($stats['total']??0)),'suffix'=>'+','label'=>'Shipments Tracked'],
        ['icon'=>'fa-circle-check','value'=>number_format((int)($stats['delivered']??0)),'suffix'=>'','label'=>'Delivered'],
        ['icon'=>'fa-earth-americas','value'=> $countryCount ?? 35,'suffix'=>'+','label'=>'Countries'],
        ['icon'=>'fa-clock','value'=>'24','suffix'=>'/7','label'=>'Support'],
      ]; foreach ($statsData as $s): ?>
      <div class="col-6 col-lg-3">
        <div class="tx-stat-item">
          <i class="fas <?= $s['icon'] ?>" style="font-size:1.8rem;margin-bottom:.8rem;color:rgba(255,255,255,.3)"></i>
          <span class="number" data-counter data-target="<?= preg_replace('/\D/','',$s['value']) ?>" data-suffix="<?= $s['suffix'] ?>"><?= $s['value'] ?><?= $s['suffix'] ?></span>
          <span class="label"><?= $s['label'] ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── PARTNERS ──────────────────────────────────────────────── -->
<?php if (!empty($partners)): ?>
<section class="tx-section tx-section-dark tx-partners">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="tx-section-title"><?= $lang->get('partners') ?></h2>
      <div class="tx-divider"></div>
      <p class="tx-section-subtitle mx-auto">Trusted by leading logistics companies worldwide</p>
    </div>
    <div class="row align-items-center justify-content-center g-4">
      <?php foreach ($partners as $p): ?>
      <div class="col-6 col-md-3 col-lg-2 text-center">
        <a href="<?= Security::e($p['website'] ?? '#') ?>" target="_blank" rel="noopener">
          <img src="<?= ASSETS_URL.'/'.Security::e($p['logo']) ?>" alt="<?= Security::e($p['name']) ?>" class="tx-partner-logo">
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── FAQ ───────────────────────────────────────────────────── -->
<?php if (!empty($faqs)): ?>
<section class="tx-section" id="faq">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category">FAQ</span>
      <h2 class="tx-section-title mt-2">Frequently Asked Questions</h2>
      <div class="tx-divider"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8 tx-faq">
        <div class="accordion" id="faqAccordion">
          <?php foreach ($faqs as $i => $faq): ?>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button <?= $i>0?'collapsed':'' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                <i class="fas fa-question-circle me-2 text-warning"></i><?= Security::e($faq['question']) ?>
              </button>
            </h2>
            <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i===0?'show':'' ?>" data-bs-parent="#faqAccordion">
              <div class="accordion-body" style="color:#555;line-height:1.7"><?= Security::e($faq['answer']) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── BLOG ──────────────────────────────────────────────────── -->
<?php if (!empty($posts)): ?>
<section class="tx-section tx-section-dark" id="news">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category"><?= $lang->get('latest_news') ?></span>
      <h2 class="tx-section-title mt-2"><?= $lang->get('latest_news') ?></h2>
      <div class="tx-divider"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($posts as $post): ?>
      <div class="col-lg-4 col-md-6">
        <div class="tx-card tx-blog-card h-100">
          <?php if (!empty($post['featured_image'])): ?>
          <img src="<?= ASSETS_URL.'/'.Security::e($post['featured_image']) ?>" class="card-img-top" alt="<?= Security::e($post['title']) ?>" loading="lazy">
          <?php else: ?>
          <div style="height:220px;background:linear-gradient(135deg,var(--tx-primary),#2563eb);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-newspaper" style="font-size:3rem;color:rgba(255,255,255,.3)"></i>
          </div>
          <?php endif; ?>
          <div class="p-4">
            <?php if (!empty($post['category_name'])): ?>
            <span class="tx-blog-category mb-2"><?= Security::e($post['category_name']) ?></span>
            <?php endif; ?>
            <h6 class="mt-2" style="font-weight:700;color:var(--tx-primary);line-height:1.4">
              <a href="<?= BASE_URL ?>/blog/<?= Security::e($post['slug']) ?>" style="color:inherit">
                <?= Security::e($post['title']) ?>
              </a>
            </h6>
            <p class="tx-blog-meta"><i class="far fa-calendar me-1"></i><?= date('M d, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></p>
            <?php if (!empty($post['excerpt'])): ?>
            <p style="font-size:.88rem;color:#64748b;margin:.5rem 0 1rem"><?= Security::e(substr($post['excerpt'],0,120)) ?>...</p>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/blog/<?= Security::e($post['slug']) ?>" class="tx-btn-primary" style="font-size:.82rem;padding:.4rem 1rem;">
              <?= $lang->get('read_more') ?> <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="<?= BASE_URL ?>/blog" class="tx-btn-outline">View All Articles <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── CONTACT CTA ────────────────────────────────────────────── -->
<section style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));padding:80px 0;">
  <div class="container text-center">
    <h2 style="color:#fff;font-weight:800;font-size:2rem">Ready to Get Started?</h2>
    <p style="color:rgba(255,255,255,.75);font-size:1.05rem;margin:.8rem 0 2rem">Connect your e-commerce platform to TrackXa and give your customers world-class tracking.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="<?= BASE_URL ?>/contact" class="tx-btn-primary" style="font-size:1rem;padding:.8rem 2rem;">
        <i class="fas fa-envelope me-2"></i><?= $lang->get('contact_us') ?>
      </a>
      <a href="<?= BASE_URL ?>/api/docs" class="tx-btn-outline" style="border-color:rgba(255,255,255,.4);color:#fff;font-size:1rem;padding:.8rem 2rem;">
        <i class="fas fa-code me-2"></i>API Documentation
      </a>
    </div>
  </div>
</section>
