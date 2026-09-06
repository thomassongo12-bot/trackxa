<section style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));padding:70px 0 50px">
  <div class="container text-center text-white">
    <h1 style="font-weight:800"><i class="fas fa-circle-question me-2" style="color:var(--tx-accent)"></i>Frequently Asked Questions</h1>
    <p style="color:rgba(255,255,255,.75)">Find answers to common questions about TrackXa</p>
  </div>
</section>
<section class="tx-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 tx-faq">
        <?php if (empty($faqs)): ?>
        <div class="text-center py-5"><p class="text-muted">No FAQs available in your language.</p></div>
        <?php else: ?>
        <div class="accordion" id="faqAccordion">
          <?php foreach ($faqs as $i => $faq): ?>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button <?= $i>0?'collapsed':'' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                <i class="fas fa-question-circle me-2 text-warning"></i><?= Security::e($faq['question']) ?>
              </button>
            </h2>
            <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i===0?'show':'' ?>" data-bs-parent="#faqAccordion">
              <div class="accordion-body" style="color:#555;line-height:1.7"><?= nl2br(Security::e($faq['answer'])) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="text-center mt-5">
      <p style="font-size:1rem;color:#64748b">Still have questions?</p>
      <a href="<?= BASE_URL ?>/contact" class="tx-btn-primary" style="font-size:1rem;padding:.8rem 2rem">
        <i class="fas fa-envelope me-2"></i><?= $lang->get('contact_us') ?>
      </a>
    </div>
  </div>
</section>
