<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-key me-2 text-warning"></i>API Keys</h1>
    <p class="adm-page-subtitle">Manage access keys for connected websites</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/admin/api-logs" class="btn btn-outline-secondary"><i class="fas fa-scroll me-1"></i> View Logs</a>
    <a href="<?= BASE_URL ?>/api/docs" target="_blank" class="btn btn-outline-primary"><i class="fas fa-book me-1"></i> API Docs</a>
  </div>
</div>

<!-- Generate form -->
<div class="adm-card mb-3">
  <div class="adm-card-header"><h5 class="adm-card-title"><i class="fas fa-plus-circle text-warning"></i> Generate New Key</h5></div>
  <div class="adm-card-body">
    <form action="<?= BASE_URL ?>/admin/api-keys/generate" method="POST" class="row g-2 align-items-end">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <div class="col-md-6">
        <label class="adm-form-label">Select Website</label>
        <?php
        $db = Database::getInstance();
        $websites = $db->query("SELECT id,name FROM websites WHERE status='active' ORDER BY name")->fetchAll();
        ?>
        <select name="website_id" class="adm-form-control" required>
          <option value="">— Select a website —</option>
          <?php foreach ($websites as $w): ?>
          <option value="<?= $w['id'] ?>"><?= Security::e($w['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-warning text-white fw-bold w-100">
          <i class="fas fa-key me-1"></i> Generate Key
        </button>
      </div>
    </form>
    <?php if ($newKey = Session::getInstance()->getFlash('new_key')): ?>
    <?php $nk = json_decode($newKey, true); ?>
    <div class="alert alert-success mt-3 p-3">
      <strong><i class="fas fa-check-circle me-1"></i> New API Key Generated — Save the secret now, it won't be shown again!</strong>
      <div class="mt-2 p-2 bg-dark rounded" style="font-family:monospace;font-size:.85rem">
        <div><b style="color:#aaa">API Key:</b> <span style="color:#90EE90"><?= Security::e($nk['api_key']) ?></span></div>
        <div><b style="color:#aaa">Secret:</b>  <span style="color:#FFD700"><?= Security::e($nk['api_secret']) ?></span></div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Keys table -->
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Website</th><th>API Key</th><th>Calls Today</th><th>Total Calls</th><th>Last Used</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($keys as $k): ?>
      <tr>
        <td><div style="font-weight:600"><?= Security::e($k['website_name'] ?? '—') ?></div><small class="text-muted"><?= Security::e($k['domain'] ?? '') ?></small></td>
        <td><code style="font-size:.8rem"><?= Security::e(substr($k['api_key'],0,12).'••••••••') ?></code>
          <button class="btn btn-sm" style="border:none;background:none;padding:0;margin-left:.3rem" data-copy="<?= Security::e($k['api_key']) ?>" title="Copy full key"><i class="fas fa-copy" style="font-size:.75rem;color:#888"></i></button>
        </td>
        <td><?= number_format($k['calls_today']) ?> / <?= number_format($k['rate_limit']) ?></td>
        <td><?= number_format($k['calls_total']) ?></td>
        <td style="font-size:.8rem;color:#888"><?= $k['last_used_at'] ? date('M d H:i', strtotime($k['last_used_at'])) : 'Never' ?></td>
        <td><span class="adm-badge adm-badge-<?= $k['status']==='active'?'success':($k['status']==='revoked'?'danger':'secondary') ?>"><?= ucfirst($k['status']) ?></span></td>
        <td>
          <?php if ($k['status']==='active'): ?>
          <form method="POST" action="<?= BASE_URL ?>/admin/api-keys/<?= $k['id'] ?>/revoke" style="display:inline">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <button class="btn btn-sm btn-outline-danger" data-confirm="Revoke this API key?">Revoke</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
