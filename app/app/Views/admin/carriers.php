<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-truck me-2 text-warning"></i>Carriers</h1></div>
  <button class="btn btn-warning text-white fw-bold" data-bs-toggle="modal" data-bs-target="#addCarrierModal">
    <i class="fas fa-plus me-1"></i> Add Carrier
  </button>
</div>
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Name</th><th>Code</th><th>Website</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($carriers as $c): ?>
      <tr>
        <td style="font-weight:600"><?= Security::e($c['name']) ?></td>
        <td><code><?= Security::e($c['code']) ?></code></td>
        <td><?= $c['website'] ? '<a href="'.Security::e($c['website']).'" target="_blank">'.Security::e($c['website']).'</a>' : '—' ?></td>
        <td><span class="adm-badge adm-badge-<?= $c['status']?'success':'secondary' ?>"><?= $c['status']?'Active':'Inactive' ?></span></td>
        <td>
          <div class="actions">
            <form method="POST" action="<?= BASE_URL ?>/admin/carriers">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <button type="submit" class="danger" data-confirm="Delete this carrier?"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCarrierModal">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Carrier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="<?= BASE_URL ?>/admin/carriers">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <input type="hidden" name="action" value="add">
      <div class="modal-body row g-3">
        <div class="col-md-6"><label class="adm-form-label">Name</label><input type="text" name="name" class="adm-form-control" required></div>
        <div class="col-md-6"><label class="adm-form-label">Code</label><input type="text" name="code" class="adm-form-control" required placeholder="e.g. DHL"></div>
        <div class="col-12"><label class="adm-form-label">Website</label><input type="url" name="website" class="adm-form-control"></div>
        <div class="col-12"><label class="adm-form-label">Tracking URL ({tracking} placeholder)</label><input type="text" name="tracking_url" class="adm-form-control"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning text-white fw-bold">Add</button>
      </div>
    </form>
  </div></div>
</div>
