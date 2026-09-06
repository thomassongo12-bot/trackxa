<!-- Track Page -->
<section style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));padding:80px 0 60px;">
  <div class="container">
    <div class="text-center text-white mb-4">
      <h1 style="font-weight:800;font-size:2.2rem"><i class="fas fa-magnifying-glass-location me-2" style="color:var(--tx-accent)"></i><?= $lang->get('track_your_shipment') ?></h1>
      <p style="color:rgba(255,255,255,.75);font-size:1rem"><?= $lang->get('enter_tracking') ?></p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <?php if (!empty($error)): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
          <i class="fas fa-exclamation-triangle"></i>
          <span><?= $error ?></span>
        </div>
        <?php endif; ?>
        <div class="tx-track-box">
          <form action="<?= BASE_URL ?>/track" method="POST" id="tx-track-form">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <div class="d-flex gap-2">
              <input type="text" name="tracking_number" id="tracking_number" class="tx-track-input flex-grow-1"
                     placeholder="e.g. TXA1A2B3C4D5E6 or REF-123456"
                     value="<?= Security::e($number ?? '') ?>" autocomplete="off" autofocus>
              <button type="submit" class="tx-track-btn">
                <i class="fas fa-search me-1"></i> <?= $lang->get('track_btn') ?>
              </button>
            </div>
            <p class="mt-2 mb-0" style="color:rgba(255,255,255,.5);font-size:.75rem">
              <i class="fas fa-info-circle me-1"></i> You can search by tracking number, reference number, or order number.
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features row -->
<section class="tx-section-dark py-5">
  <div class="container">
    <div class="row g-3 text-center">
      <?php foreach ([
        ['icon'=>'fa-bolt','title'=>'Instant Results','desc'=>'Real-time data'],
        ['icon'=>'fa-globe','title'=>'35+ Countries','desc'=>'Global coverage'],
        ['icon'=>'fa-lock','title'=>'Secure','desc'=>'Private & encrypted'],
        ['icon'=>'fa-bell','title'=>'Email Alerts','desc'=>'Auto notifications'],
      ] as $f): ?>
      <div class="col-6 col-md-3">
        <div class="p-3">
          <i class="fas <?= $f['icon'] ?>" style="font-size:1.5rem;color:var(--tx-primary);margin-bottom:.5rem"></i>
          <div style="font-weight:700;font-size:.9rem;color:var(--tx-primary)"><?= $f['title'] ?></div>
          <div style="font-size:.78rem;color:#64748b"><?= $f['desc'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
