<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-globe me-2 text-warning"></i>Connected Websites</h1>
    <p class="adm-page-subtitle">E-commerce platforms using TrackXa API</p>
  </div>
  <a href="<?= BASE_URL ?>/admin/websites/create" class="btn btn-warning text-white fw-bold">
    <i class="fas fa-plus me-1"></i> Add Website
  </a>
</div>
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Name</th><th class="hide-mobile">Domain</th><th class="hide-mobile">API Keys</th><th>Status</th><th class="hide-mobile">Added</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($websites)): ?>
      <tr><td colspan="6" class="text-center py-4 text-muted">No websites added yet.</td></tr>
      <?php endif; ?>
      <?php foreach ($websites as $w): ?>
      <tr>
        <td><div style="font-weight:600"><?= Security::e($w['name']) ?></div></td>
        <td><a href="https://<?= Security::e($w['domain']) ?>" target="_blank" rel="noopener"><?= Security::e($w['domain']) ?></a></td>
        <td><span class="adm-badge adm-badge-info"><?= $w['key_count'] ?> active</span></td>
        <td>
          <?php $sc = $w['status']==='active'?'success':($w['status']==='suspended'?'danger':'secondary'); ?>
          <span class="adm-badge adm-badge-<?= $sc ?>"><?= ucfirst($w['status']) ?></span>
        </td>
        <td style="font-size:.8rem;color:#888"><?= date('M d, Y', strtotime($w['created_at'])) ?></td>
        <td>
          <div class="actions">
            <a href="<?= BASE_URL ?>/admin/websites/<?= $w['id'] ?>/edit" title="Manage"><i class="fas fa-pen"></i></a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
