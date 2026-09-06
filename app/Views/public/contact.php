<section class="tx-page-hero">
  <div class="container text-center">
    <h1><i class="fas fa-envelope me-2" style="color:var(--tx-accent)"></i><?= $lang->get('contact_us') ?></h1>
    <p><?= $lang->get('contact_subtitle') ?? 'Have a question? We would love to hear from you.' ?></p>
  </div>
</section>
<section class="tx-section" style="padding:50px 0">
  <div class="container">
    <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="fas fa-check-circle flex-shrink-0"></i><span><?= Security::e($success) ?></span>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger mb-4"><?= Security::e($error) ?></div>
    <?php endif; ?>
    <div class="row g-4 g-lg-5">
      <div class="col-12 col-lg-5 order-2 order-lg-1">
        <h3 style="font-weight:800;color:var(--tx-primary);font-size:clamp(1.2rem,3vw,1.5rem)">Get in Touch</h3>
        <p style="color:#64748b;font-size:.95rem">Our team is here to help with any questions about shipment tracking, API integration, or general inquiries.</p>
        <?php $contacts = [
          ['icon'=>'fa-map-marker-alt','val'=>$settings['site_address']??''],
          ['icon'=>'fa-phone','val'=>$settings['site_phone']??''],
          ['icon'=>'fa-envelope','val'=>$settings['site_email']??''],
        ]; foreach ($contacts as $c): if(empty($c['val']))continue; ?>
        <div class="d-flex gap-3 align-items-start mb-3">
          <div style="width:42px;height:42px;background:rgba(26,60,110,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="fas <?= $c['icon'] ?>" style="color:var(--tx-primary)"></i>
          </div>
          <div style="font-size:.9rem;color:#374151;padding-top:.5rem"><?= Security::e($c['val']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="col-12 col-lg-7 order-1 order-lg-2">
        <div class="tx-card p-3 p-md-4">
          <h4 style="font-weight:700;color:var(--tx-primary);margin-bottom:1.5rem;font-size:1.1rem"><?= $lang->get('send_message') ?></h4>
          <form action="<?= BASE_URL ?>/contact" method="POST" novalidate>
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <div class="row g-3">
              <div class="col-12 col-sm-6">
                <label class="form-label fw-semibold" style="font-size:.85rem"><?= $lang->get('name') ?> <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="Your full name">
              </div>
              <div class="col-12 col-sm-6">
                <label class="form-label fw-semibold" style="font-size:.85rem"><?= $lang->get('email') ?> <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required placeholder="your@email.com">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold" style="font-size:.85rem"><?= $lang->get('subject') ?></label>
                <input type="text" name="subject" class="form-control" placeholder="How can we help?">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold" style="font-size:.85rem"><?= $lang->get('message') ?> <span class="text-danger">*</span></label>
                <textarea name="message" class="form-control" rows="5" required placeholder="Write your message here..."></textarea>
              </div>
              <div class="col-12 mt-1">
                <button type="submit" class="tx-btn-primary w-100" style="padding:.85rem;font-size:.95rem;justify-content:center">
                  <i class="fas fa-paper-plane me-2"></i><?= $lang->get('send_message') ?>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
