<?php $isEdit = !empty($website); $w = $website ?? []; ?>
<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-globe me-2 text-warning"></i><?= $isEdit ? 'Manage: '.Security::e($w['name']) : 'Add Website' ?></h1>
  </div>
  <a href="<?= BASE_URL ?>/admin/websites" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>
<div class="row g-3">
  <div class="col-lg-6">
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-globe text-warning"></i> Website Info</div>
      <form action="<?= BASE_URL ?>/admin/websites/<?= $isEdit ? $w['id'].'/edit' : 'create' ?>" method="POST">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <div class="mb-3">
          <label class="adm-form-label">Website Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="adm-form-control" value="<?= Security::e($w['name']??'') ?>" required placeholder="e.g. ParruParrot">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Domain <span class="text-danger">*</span></label>
          <input type="text" name="domain" class="adm-form-control" value="<?= Security::e($w['domain']??'') ?>" required placeholder="e.g. parruparrot.com">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Contact Email</label>
          <input type="email" name="contact_email" class="adm-form-control" value="<?= Security::e($w['contact_email']??'') ?>">
        </div>
        <?php if ($isEdit): ?>
        <div class="mb-3">
          <label class="adm-form-label">Status</label>
          <select name="status" class="adm-form-control">
            <option value="active" <?= ($w['status']??'')==='active'?'selected':'' ?>>Active</option>
            <option value="inactive" <?= ($w['status']??'')==='inactive'?'selected':'' ?>>Inactive</option>
            <option value="suspended" <?= ($w['status']??'')==='suspended'?'selected':'' ?>>Suspended</option>
          </select>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-warning text-white fw-bold">
          <i class="fas fa-save me-1"></i> <?= $isEdit ? 'Update' : 'Add Website' ?>
        </button>
      </form>
    </div>
  </div>
  <?php if ($isEdit): ?>
  <div class="col-lg-6">
    <!-- API Keys -->
    <div class="adm-form-section mb-3">
      <div class="adm-form-section-title"><i class="fas fa-key text-warning"></i> API Keys</div>
      <?php if (!empty($keys)): ?>
      <?php foreach ($keys as $k): ?>
      <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded">
        <div>
          <code style="font-size:.8rem"><?= Security::e(substr($k['api_key'],0,12).'••••') ?></code>
          <span class="adm-badge adm-badge-<?= $k['status']==='active'?'success':'secondary' ?> ms-2"><?= $k['status'] ?></span>
          <div style="font-size:.72rem;color:#888">Calls today: <?= $k['calls_today'] ?> / <?= $k['rate_limit'] ?></div>
        </div>
        <?php if ($k['status']==='active'): ?>
        <form method="POST" action="<?= BASE_URL ?>/admin/api-keys/<?= $k['id'] ?>/revoke">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
          <button class="btn btn-sm btn-outline-danger" data-confirm="Revoke this key?">Revoke</button>
        </form>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <p class="text-muted" style="font-size:.85rem">No API keys generated yet.</p>
      <?php endif; ?>
      <form method="POST" action="<?= BASE_URL ?>/admin/api-keys/generate" class="mt-2">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <input type="hidden" name="website_id" value="<?= $w['id'] ?>">
        <button class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i> Generate New Key</button>
      </form>
      <?php if ($newKey = Session::getInstance()->getFlash('new_key')): ?>
      <?php $nk = json_decode($newKey, true); ?>
      <div class="alert alert-success mt-3 p-3" style="font-size:.82rem">
        <strong><i class="fas fa-key me-1"></i> New API Key (save secret now!)</strong><br>
        <b>Key:</b> <code><?= Security::e($nk['api_key']) ?></code><br>
        <b>Secret:</b> <code><?= Security::e($nk['api_secret']) ?></code>
      </div>
      <?php endif; ?>
    </div>
    <!-- Webhooks -->
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-webhook text-warning"></i> Webhooks</div>
      <?php if (!empty($hooks)): ?>
      <?php foreach ($hooks as $h): ?>
      <div class="p-2 mb-2 bg-light rounded" style="font-size:.82rem">
        <div><strong><?= Security::e($h['url']) ?></strong></div>
        <div class="text-muted"><?= Security::e($h['events'] ?? 'all') ?></div>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <p class="text-muted" style="font-size:.85rem">No webhooks configured.</p>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
