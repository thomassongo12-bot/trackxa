<section style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));padding:70px 0 50px;">
  <div class="container text-center text-white">
    <h1 style="font-weight:800"><i class="fas fa-envelope me-2" style="color:var(--tx-accent)"></i><?= $lang->get('contact_us') ?></h1>
    <p style="color:rgba(255,255,255,.75)">Have a question? We'd love to hear from you.</p>
  </div>
</section>

<section class="tx-section">
  <div class="container">
    <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="fas fa-check-circle"></i><span><?= Security::e($success) ?></span>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger mb-4"><?= Security::e($error) ?></div>
    <?php endif; ?>
    <div class="row g-5">
      <div class="col-lg-5">
        <h3 style="font-weight:800;color:var(--tx-primary)">Get in Touch</h3>
        <p style="color:#64748b">Our team is here to help with any questions about shipment tracking, API integration, or general inquiries.</p>
        <?php $contacts = [
          ['icon'=>'fa-map-marker-alt','val'=>$settings['site_address']??''],
          ['icon'=>'fa-phone','val'=>$settings['site_phone']??''],
          ['icon'=>'fa-envelope','val'=>$settings['site_email']??''],
        ]; foreach ($contacts as $c): if(empty($c['val']))continue; ?>
        <div class="d-flex gap-3 align-items-start mb-3">
          <div style="width:44px;height:44px;background:rgba(26,60,110,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="fas <?= $c['icon'] ?>" style="color:var(--tx-primary)"></i>
          </div>
          <div style="font-size:.9rem;color:#374151;padding-top:.6rem"><?= Security::e($c['val']) ?></div>
        </div>
        <?php endforeach; ?>
        <?php if (!empty($settings['google_maps_key'])): ?>
        <div style="border-radius:12px;overflow:hidden;margin-top:1.5rem">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1!2d-73.9!3d40.7!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQyJzAwLjAiTiA3M8KwNTQnMDAuMCJX!5e0!3m2!1sen!2sus!4v1"
            width="100%" height="200" style="border:0" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-7">
        <div class="tx-card p-4 p-lg-5">
          <h4 style="font-weight:700;color:var(--tx-primary);margin-bottom:1.5rem"><?= $lang->get('send_message') ?></h4>
          <form action="<?= BASE_URL ?>/contact" method="POST" novalidate>
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold" style="font-size:.85rem"><?= $lang->get('name') ?> <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="Your full name">
              </div>
              <div class="col-md-6">
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
              <div class="col-12 mt-2">
                <button type="submit" class="tx-btn-primary w-100" style="padding:.85rem;font-size:1rem;justify-content:center">
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
