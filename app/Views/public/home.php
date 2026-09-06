<?php
$siteName = Security::e($settings['site_name'] ?? 'TrackXa');
$statuses = ShipmentModel::STATUSES;
?>
<!-- ── HERO SLIDER ──────────────────────────────────────────── -->
<section id="tx-hero-section" style="position:relative;min-height:92vh;display:flex;align-items:center;overflow:hidden;background:#0f2647;">

  <!-- Images slides (superposées, toutes position absolute) -->
  <div style="position:absolute;inset:0;z-index:0;">
    <img id="tx-sl-0" src="<?= ASSETS_URL ?>/images/slide1.jpg"
         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;transition:opacity 1.4s ease;opacity:1;" alt="">
    <img id="tx-sl-1" src="<?= ASSETS_URL ?>/images/slide2.jpg"
         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;transition:opacity 1.4s ease;opacity:0;" alt="">
    <img id="tx-sl-2" src="<?= ASSETS_URL ?>/images/slide3.jpg"
         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;transition:opacity 1.4s ease;opacity:0;" alt="">
  </div>

  <!-- Overlay gradient -->
  <div style="position:absolute;inset:0;z-index:1;background:linear-gradient(135deg,rgba(15,38,71,.85) 0%,rgba(26,60,110,.70) 50%,rgba(37,99,235,.50) 100%);pointer-events:none;"></div>

  <!-- Contenu centré -->
  <div class="container" style="position:relative;z-index:2;padding-top:4rem;padding-bottom:4rem;">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8 col-xl-7 text-center">

        <!-- Badge -->
        <div class="mb-3">
          <span style="display:inline-flex;align-items:center;gap:.4rem;background:rgba(232,160,32,.2);color:#e8a020;font-size:.82rem;font-weight:600;padding:.4rem 1rem;border-radius:100px;border:1px solid rgba(232,160,32,.3);">
            <i class="fas fa-bolt"></i> <?= $lang->get('hero_badge') ?>
          </span>
        </div>

        <!-- Titre -->
        <h1 style="font-size:clamp(2rem,5vw,3.4rem);font-weight:800;color:#fff;line-height:1.15;margin-bottom:1rem;">
          <?= $lang->get('hero_title') ?>
        </h1>

        <!-- Sous-titre -->
        <p style="color:rgba(255,255,255,.82);font-size:1.05rem;line-height:1.65;margin-bottom:2rem;max-width:560px;margin-left:auto;margin-right:auto;">
          <?= $lang->get('hero_subtitle') ?>
        </p>

        <!-- Formulaire -->
        <form action="<?= BASE_URL ?>/track" method="POST" id="tx-track-form" style="max-width:580px;margin:0 auto 1.2rem;">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
          <div class="tx-hero-search-wrap" style="display:flex;align-items:center;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);border-radius:14px;padding:.45rem .45rem .45rem 1.1rem;">
            <input type="text" name="tracking_number" id="tracking_number"
                   style="flex:1;background:none;border:none;outline:none;color:#fff;font-size:.95rem;min-width:0;"
                   placeholder="<?= $lang->get('enter_tracking') ?>"
                   autocomplete="off" required>
            <button type="submit" style="background:#e8a020;color:#fff;border:none;border-radius:10px;padding:.72rem 1.5rem;font-size:.9rem;font-weight:700;cursor:pointer;white-space:nowrap;display:inline-flex;align-items:center;gap:.5rem;flex-shrink:0;">
              <i class="fas fa-search"></i><?= $lang->get('track_btn') ?>
            </button>
          </div>
          <p style="margin:.6rem 0 0;color:rgba(255,255,255,.45);font-size:.72rem;">
            <i class="fas fa-shield-check" style="color:#e8a020;margin-right:.3rem;"></i>
            <?= $lang->get('hero_supports') ?>
          </p>
        </form>

        <!-- Stats -->
        <div style="display:inline-flex;gap:2.5rem;flex-wrap:wrap;justify-content:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.15);">
          <div>
            <div style="font-size:1.9rem;font-weight:800;color:#e8a020;line-height:1;" data-counter data-target="957649" data-suffix="+">957,649+</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.06em;margin-top:.25rem;"><?= $lang->get('stat_shipments') ?></div>
          </div>
          <div style="width:1px;background:rgba(255,255,255,.15);"></div>
          <div>
            <div style="font-size:1.9rem;font-weight:800;color:#e8a020;line-height:1;" data-counter data-target="116" data-suffix="+">116+</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.06em;margin-top:.25rem;"><?= $lang->get('stat_countries') ?></div>
          </div>
          <div style="width:1px;background:rgba(255,255,255,.15);"></div>
          <div>
            <div style="font-size:1.9rem;font-weight:800;color:#e8a020;line-height:1;" data-counter data-target="99" data-suffix="%">99%</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.06em;margin-top:.25rem;"><?= $lang->get('stat_accuracy') ?></div>
          </div>
        </div>

        <!-- Dots -->
        <div style="display:flex;gap:.6rem;justify-content:center;margin-top:1.8rem;" id="tx-slider-dots">
          <button class="tx-dot active" data-sl="0" style="width:28px;height:10px;border-radius:5px;background:#e8a020;border:none;cursor:pointer;padding:0;transition:all .3s;"></button>
          <button class="tx-dot" data-sl="1" style="width:10px;height:10px;border-radius:5px;background:transparent;border:2px solid rgba(255,255,255,.6);cursor:pointer;padding:0;transition:all .3s;"></button>
          <button class="tx-dot" data-sl="2" style="width:10px;height:10px;border-radius:5px;background:transparent;border:2px solid rgba(255,255,255,.6);cursor:pointer;padding:0;transition:all .3s;"></button>
        </div>

      </div>
    </div>
  </div>

</section>

<script>
// Slider hero
(function(){
  var slides  = [document.getElementById('tx-sl-0'), document.getElementById('tx-sl-1'), document.getElementById('tx-sl-2')];
  var dots    = document.querySelectorAll('#tx-slider-dots .tx-dot');
  var current = 0;
  var timer   = null;

  function goTo(n) {
    slides[current].style.opacity = '0';
    dots[current].style.width      = '10px';
    dots[current].style.background = 'transparent';
    dots[current].style.border     = '2px solid rgba(255,255,255,.6)';
    current = (n + 3) % 3;
    slides[current].style.opacity = '1';
    dots[current].style.width      = '28px';
    dots[current].style.background = '#e8a020';
    dots[current].style.border     = 'none';
  }

  function next() { goTo(current + 1); }

  dots.forEach(function(d, i){ d.addEventListener('click', function(){ clearInterval(timer); goTo(i); timer = setInterval(next, 5000); }); });

  timer = setInterval(next, 5000);
})();
</script>


<!-- ── SERVICES ──────────────────────────────────────────────── -->
<section class="tx-section" id="services">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category"><?= $lang->get('our_services') ?></span>
      <h2 class="tx-section-title mt-2"><?= $lang->get('our_services') ?></h2>
      <div class="tx-divider"></div>
      <p class="tx-section-subtitle mx-auto"><?= $lang->get('services_subtitle') ?></p>
    </div>
    <div class="row g-4">
      <?php $services = [
        ['img'=>'s-1.jpg','icon'=>'fa-plane','color'=>'linear-gradient(135deg,#1a3c6e,#2563eb)','title'=>$lang->get('service_1_title'),'desc'=>$lang->get('service_1_desc'),'badge'=>$lang->get('service_1_badge')],
        ['img'=>'s-2.png','icon'=>'fa-ship','color'=>'linear-gradient(135deg,#0d6efd,#17a2b8)','title'=>$lang->get('service_2_title'),'desc'=>$lang->get('service_2_desc'),'badge'=>$lang->get('service_2_badge')],
        ['img'=>'s-3.jpeg','icon'=>'fa-truck-fast','color'=>'linear-gradient(135deg,#e8a020,#f97316)','title'=>$lang->get('service_3_title'),'desc'=>$lang->get('service_3_desc'),'badge'=>$lang->get('service_3_badge')],
        ['img'=>'s-4.jpg','icon'=>'fa-warehouse','color'=>'linear-gradient(135deg,#6610f2,#6f42c1)','title'=>$lang->get('service_4_title'),'desc'=>$lang->get('service_4_desc'),'badge'=>$lang->get('service_4_badge')],
        ['img'=>'s-5.jpeg','icon'=>'fa-file-shield','color'=>'linear-gradient(135deg,#17a2b8,#20c997)','title'=>$lang->get('service_5_title'),'desc'=>$lang->get('service_5_desc'),'badge'=>$lang->get('service_5_badge')],
        ['img'=>'s-6.jpg','icon'=>'fa-shield-halved','color'=>'linear-gradient(135deg,#28a745,#20c997)','title'=>$lang->get('service_6_title'),'desc'=>$lang->get('service_6_desc'),'badge'=>$lang->get('service_6_badge')],
      ]; foreach ($services as $s): ?>
      <div class="col-lg-4 col-md-6">
        <div class="tx-card h-100" style="overflow:hidden;transition:transform .3s,box-shadow .3s;"
             onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 50px rgba(0,0,0,.13)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''">
          <div class="tx-service-img" style="position:relative;height:190px;overflow:hidden;">
            <img src="<?= ASSETS_URL ?>/images/<?= $s['img'] ?>" alt="<?= Security::e($s['title']) ?>" style="width:100%;height:100%;object-fit:cover;" loading="lazy" onerror="this.parentElement.style.background='<?= str_replace('"','\"',$s['color']) ?>';this.style.display='none'">
            <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.08) 0%,rgba(0,0,0,.55) 100%)"></div>
            <div style="position:absolute;top:14px;left:14px;width:42px;height:42px;border-radius:11px;background:<?= $s['color'] ?>;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(0,0,0,.25);"><i class="fas <?= $s['icon'] ?>" style="color:#fff;font-size:1rem"></i></div>
            <div style="position:absolute;bottom:12px;right:12px;background:rgba(255,255,255,.18);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.3);border-radius:100px;padding:.22rem .75rem;font-size:.68rem;font-weight:700;color:#fff;"><?= Security::e($s['badge']) ?></div>
          </div>
          <div style="padding:1.25rem 1.4rem 1.5rem;">
            <h5 style="font-weight:800;color:var(--tx-primary);margin-bottom:.55rem;font-size:1.02rem;"><?= Security::e($s['title']) ?></h5>
            <p style="color:#64748b;font-size:.88rem;line-height:1.65;margin:0;"><?= $s['desc'] ?></p>
          </div>
          <div style="height:3px;background:<?= $s['color'] ?>"></div>
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
        <h2 class="tx-section-title mt-2"><?= $lang->get('why_trust_businesses') ?></h2>
        <div class="tx-divider" style="margin:1rem 0;"></div>
        <?php $features = [
          ['icon'=>'fa-bolt',  'title'=>$lang->get('why_feat1_title'), 'desc'=>$lang->get('why_feat1_desc')],
          ['icon'=>'fa-lock',  'title'=>$lang->get('why_feat2_title'), 'desc'=>$lang->get('why_feat2_desc')],
          ['icon'=>'fa-globe', 'title'=>$lang->get('why_feat3_title'), 'desc'=>$lang->get('why_feat3_desc')],
          ['icon'=>'fa-plug',  'title'=>$lang->get('why_feat4_title'), 'desc'=>$lang->get('why_feat4_desc')],
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
          <img src="<?= ASSETS_URL ?>/images/p2en.png" alt="" style="width:100%;border-radius:20px;box-shadow:0 20px 60px rgba(26,60,110,.2);object-fit:cover;max-height:480px;" loading="lazy" onerror="this.style.display='none'">
          <div style="position:absolute;bottom:24px;left:24px;background:#fff;border-radius:14px;padding:.9rem 1.3rem;box-shadow:0 8px 30px rgba(0,0,0,.15);display:flex;align-items:center;gap:.9rem;">
            <div style="width:44px;height:44px;border-radius:11px;background:linear-gradient(135deg,var(--tx-primary),#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fas fa-circle-check" style="color:#fff;font-size:1.2rem"></i></div>
            <div>
              <div style="font-size:1.3rem;font-weight:900;color:var(--tx-primary);line-height:1">99.2%</div>
              <div style="font-size:.72rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.05em"><?= $lang->get('stat_success_rate') ?></div>
            </div>
          </div>
          <div style="position:absolute;top:24px;right:24px;background:var(--tx-accent);border-radius:14px;padding:.75rem 1.1rem;box-shadow:0 8px 24px rgba(232,160,32,.35);text-align:center;">
            <div style="font-size:1.4rem;font-weight:900;color:#fff;line-height:1">945 514+</div>
            <div style="font-size:.68rem;color:rgba(255,255,255,.85);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-top:.2rem"><?= $lang->get('stat_total_shipments') ?></div>
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
      <h2 class="tx-section-title mt-2"><?= $lang->get('how_it_works_title') ?></h2>
      <div class="tx-divider"></div>
      <p class="tx-section-subtitle mx-auto"><?= $lang->get('how_it_works_subtitle') ?></p>
    </div>
    <div class="row g-4 mb-5">
      <?php $steps = [
        ['num'=>1,'img'=>'how1.png','color'=>'#1a3c6e','gradient'=>'135deg,#1a3c6e,#2563eb','title'=>$lang->get('how_step1_title'),'desc'=>$lang->get('how_step1_desc'),'badge'=>$lang->get('how_step1_badge'),'badge_icon'=>'fa-info-circle','badge_bg'=>'rgba(26,60,110,.06)','badge_color'=>'var(--tx-primary)'],
        ['num'=>2,'img'=>'how2.png','color'=>'#e8a020','gradient'=>'135deg,#e8a020,#f97316','title'=>$lang->get('how_step2_title'),'desc'=>$lang->get('how_step2_desc'),'badge'=>$lang->get('how_step2_badge'),'badge_icon'=>'fa-clock','badge_bg'=>'rgba(232,160,32,.1)','badge_color'=>'#c8860a'],
        ['num'=>3,'img'=>'how3.png','color'=>'#17a2b8','gradient'=>'135deg,#17a2b8,#0d6efd','title'=>$lang->get('how_step3_title'),'desc'=>$lang->get('how_step3_desc'),'badge'=>$lang->get('how_step3_badge'),'badge_icon'=>'fa-satellite-dish','badge_bg'=>'rgba(23,162,184,.1)','badge_color'=>'#0c5460'],
        ['num'=>4,'img'=>'how4.png','color'=>'#28a745','gradient'=>'135deg,#28a745,#20c997','title'=>$lang->get('how_step4_title'),'desc'=>$lang->get('how_step4_desc'),'badge'=>$lang->get('how_step4_badge'),'badge_icon'=>'fa-bell','badge_bg'=>'rgba(40,167,69,.1)','badge_color'=>'#155724'],
      ]; foreach ($steps as $s): ?>
      <div class="col-lg-3 col-md-6">
        <div class="tx-card h-100 p-4 text-center" style="border-top:4px solid <?= $s['color'] ?>;transition:transform .3s,box-shadow .3s" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 20px 50px rgba(0,0,0,.13)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
          <div class="d-flex align-items-center justify-content-center mb-3">
            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(<?= $s['gradient'] ?>);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;box-shadow:0 4px 12px rgba(0,0,0,.2)"><?= $s['num'] ?></div>
          </div>
          <div style="height:110px;display:flex;align-items:center;justify-content:center;margin-bottom:1.2rem">
            <img src="<?= ASSETS_URL ?>/images/<?= $s['img'] ?>" alt="<?= Security::e($s['title']) ?>" style="max-height:110px;max-width:100%;object-fit:contain" loading="lazy" onerror="this.style.display='none'">
          </div>
          <h5 style="font-weight:800;color:var(--tx-primary);margin-bottom:.8rem;font-size:1rem"><?= $s['title'] ?></h5>
          <p style="color:#64748b;font-size:.88rem;line-height:1.7;margin-bottom:1rem"><?= $s['desc'] ?></p>
          <div style="background:<?= $s['badge_bg'] ?>;border-radius:8px;padding:.5rem .9rem;font-size:.75rem;color:<?= $s['badge_color'] ?>;font-weight:600"><i class="fas <?= $s['badge_icon'] ?> me-1"></i><?= $s['badge'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));border-radius:20px;padding:2.5rem;color:#fff">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <h4 style="font-weight:800;margin-bottom:1.2rem"><?= $lang->get('why_trust_title') ?></h4>
          <div class="row g-3">
            <?php foreach ([['fa-shield-check',$lang->get('why_trust_1')],['fa-globe',$lang->get('why_trust_2')],['fa-bolt',$lang->get('why_trust_3')],['fa-headset',$lang->get('why_trust_4')]] as [$icon,$text]): ?>
            <div class="col-md-6"><div class="d-flex align-items-start gap-2"><i class="fas <?= $icon ?>" style="color:var(--tx-accent);font-size:1rem;margin-top:.25rem;flex-shrink:0"></i><span style="font-size:.88rem;color:rgba(255,255,255,.85);line-height:1.5"><?= $text ?></span></div></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="col-lg-5 text-center">
          <div style="background:rgba(255,255,255,.08);border-radius:16px;padding:1.8rem">
            <div style="font-size:2.6rem;font-weight:900;color:var(--tx-accent);line-height:1" data-counter data-target="945514" data-suffix="+">945,514+</div>
            <div style="font-size:.85rem;color:rgba(255,255,255,.7);margin-bottom:1.2rem"><?= $lang->get('parcels_delivered') ?></div>
            <div style="letter-spacing:.1rem;margin-bottom:1.2rem;font-size:1.1rem">&#11088;&#11088;&#11088;&#11088;&#11088;</div>
            <a href="<?= BASE_URL ?>/track" class="tx-btn-primary" style="font-size:.95rem;padding:.8rem 1.5rem;display:inline-flex;justify-content:center;width:100%"><i class="fas fa-magnifying-glass-location me-2"></i><?= $lang->get('track_now_btn') ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── CARRIERS ─────────────────────────────────────────────── -->
<?php if (!empty($carriers)): ?>
<section class="tx-section tx-section-dark" id="carriers">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category"><?= $lang->get('carriers_badge') ?></span>
      <h2 class="tx-section-title mt-2"><?= $lang->get('carriers_title') ?></h2>
      <div class="tx-divider"></div>
      <p class="tx-section-subtitle mx-auto"><?= $lang->get('carriers_subtitle') ?></p>
    </div>
    <?php $carrierColors=['DHL'=>'#FFCC00','FEDEX'=>'#4D148C','UPS'=>'#351C15','USPS'=>'#004B87','TNT'=>'#FF6200','ARAMEX'=>'#E31837','DPD'=>'#DC0032','GLS'=>'#009BE0','ROYALMAIL'=>'#E30014','LAPOSTE'=>'#FFD700'];
    $carrierIcons=['DHL'=>'fa-shipping-fast','FEDEX'=>'fa-truck-fast','UPS'=>'fa-box','USPS'=>'fa-envelope','TNT'=>'fa-plane','ARAMEX'=>'fa-globe','DPD'=>'fa-truck','GLS'=>'fa-truck-moving','ROYALMAIL'=>'fa-mailbox','LAPOSTE'=>'fa-mailbox']; ?>
    <div class="row g-3 justify-content-center">
      <?php foreach ($carriers as $c):
        $code=$c['code']??''; $color=$carrierColors[strtoupper($code)]??'var(--tx-primary)'; $icon=$carrierIcons[strtoupper($code)]??'fa-truck'; $url=!empty($c['website'])?$c['website']:'#'; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <a href="<?= Security::e($url) ?>" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;">
          <div class="tx-card text-center p-3 h-100" style="border:2px solid transparent;transition:transform .25s,box-shadow .25s,border-color .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,.14)';this.style.borderColor='<?= $color ?>'" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='transparent'">
            <?php if (!empty($c['logo'])): ?>
              <img src="<?= ASSETS_URL.'/'.Security::e($c['logo']) ?>" alt="<?= Security::e($c['name']) ?>" style="max-height:44px;max-width:90%;object-fit:contain;margin-bottom:.75rem" loading="lazy" onerror="this.style.display='none'">
            <?php else: ?>
              <div style="height:44px;display:flex;align-items:center;justify-content:center;margin-bottom:.75rem"><div style="width:44px;height:44px;border-radius:10px;background:<?= $color ?>;display:flex;align-items:center;justify-content:center;margin:0 auto"><i class="fas <?= $icon ?>" style="color:#fff;font-size:1.1rem"></i></div></div>
            <?php endif; ?>
            <div style="font-size:.78rem;font-weight:800;color:var(--tx-primary);line-height:1.3;margin-bottom:.3rem"><?= Security::e($c['name']) ?></div>
            <div style="font-size:.68rem;color:<?= $color ?>;font-weight:600"><i class="fas fa-arrow-up-right-from-square me-1" style="font-size:.6rem"></i><?= $lang->get('carriers_visit') ?></div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4"><p style="color:#94a3b8;font-size:.82rem"><i class="fas fa-circle-info me-1"></i><?= $lang->get('carriers_note') ?></p></div>
  </div>
</section>
<?php endif; ?>
