<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-envelope-open me-2 text-warning"></i>Message</h1></div>
  <a href="<?= BASE_URL ?>/admin/contacts" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>
<div class="row g-3">
  <div class="col-lg-7">
    <div class="adm-card p-4">
      <div class="mb-3 pb-3" style="border-bottom:1px solid var(--adm-border)">
        <div style="font-size:.8rem;color:#888">From</div>
        <div style="font-weight:700"><?= Security::e($msg['name']) ?> &lt;<?= Security::e($msg['email']) ?>&gt;</div>
      </div>
      <div class="mb-3 pb-3" style="border-bottom:1px solid var(--adm-border)">
        <div style="font-size:.8rem;color:#888">Subject</div>
        <div style="font-weight:600"><?= Security::e($msg['subject'] ?? '(no subject)') ?></div>
      </div>
      <div class="mb-3">
        <div style="font-size:.8rem;color:#888">Message</div>
        <div style="line-height:1.7;margin-top:.5rem"><?= nl2br(Security::e($msg['message'])) ?></div>
      </div>
      <div style="font-size:.78rem;color:#aaa"><?= date('F d, Y H:i', strtotime($msg['created_at'])) ?> · IP: <?= Security::e($msg['ip_address']??'') ?></div>
    </div>
    <?php if ($msg['reply']): ?>
    <div class="adm-card p-4 mt-3" style="border-left:4px solid var(--adm-accent)">
      <div style="font-size:.8rem;color:#888;margin-bottom:.5rem"><i class="fas fa-reply me-1"></i>Reply sent on <?= date('M d, Y', strtotime($msg['replied_at'])) ?></div>
      <div style="line-height:1.7"><?= nl2br(Security::e($msg['reply'])) ?></div>
    </div>
    <?php endif; ?>
  </div>
  <div class="col-lg-5">
    <div class="adm-card p-4">
      <h6 style="font-weight:700;color:var(--adm-primary);margin-bottom:1rem"><i class="fas fa-reply me-2 text-warning"></i>Reply</h6>
      <form action="<?= BASE_URL ?>/admin/contacts/<?= $msg['id'] ?>/reply" method="POST">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <textarea name="reply" class="adm-form-control" rows="8" placeholder="Type your reply..." required></textarea>
        <button type="submit" class="btn btn-warning text-white fw-bold w-100 mt-3">
          <i class="fas fa-paper-plane me-1"></i> Send Reply
        </button>
      </form>
    </div>
  </div>
</div>
