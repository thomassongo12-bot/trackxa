<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-truck me-2 text-warning"></i>Carriers</h1></div>
  <button class="btn btn-warning text-white fw-bold" data-bs-toggle="modal" data-bs-target="#addCarrierModal">
    <i class="fas fa-plus me-1"></i> Add Carrier
  </button>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th style="width:60px">Logo</th>
          <th>Name</th>
          <th class="hide-mobile">Code</th>
          <th class="hide-mobile">Website</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($carriers as $c): ?>
      <tr>
        <!-- Logo -->
        <td>
          <?php if (!empty($c['logo'])): ?>
            <img src="<?= ASSETS_URL . '/' . Security::e($c['logo']) ?>"
                 alt="<?= Security::e($c['name']) ?>"
                 style="height:32px;max-width:60px;object-fit:contain;border-radius:4px;">
          <?php else: ?>
            <div style="width:38px;height:32px;background:#f0f2f5;border-radius:4px;display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-truck" style="color:#c0c8d8;font-size:.85rem"></i>
            </div>
          <?php endif; ?>
        </td>
        <td style="font-weight:600"><?= Security::e($c['name']) ?></td>
        <td class="hide-mobile"><code><?= Security::e($c['code']) ?></code></td>
        <td class="hide-mobile">
          <?= $c['website'] ? '<a href="'.Security::e($c['website']).'" target="_blank" rel="noopener" style="font-size:.82rem">'.Security::e($c['website']).'</a>' : '—' ?>
        </td>
        <td>
          <span class="adm-badge adm-badge-<?= $c['status'] ? 'success' : 'secondary' ?>">
            <?= $c['status'] ? 'Active' : 'Inactive' ?>
          </span>
        </td>
        <td>
          <div class="actions">
            <!-- Edit button -->
            <button type="button"
                    class="btn-edit-carrier"
                    title="Edit"
                    data-id="<?= $c['id'] ?>"
                    data-name="<?= Security::e($c['name']) ?>"
                    data-code="<?= Security::e($c['code']) ?>"
                    data-website="<?= Security::e($c['website'] ?? '') ?>"
                    data-tracking_url="<?= Security::e($c['tracking_url'] ?? '') ?>"
                    data-status="<?= (int)$c['status'] ?>"
                    data-logo="<?= !empty($c['logo']) ? ASSETS_URL . '/' . Security::e($c['logo']) : '' ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#editCarrierModal">
              <i class="fas fa-pen"></i>
            </button>
            <!-- Delete form -->
            <form method="POST" action="<?= BASE_URL ?>/admin/carriers" style="display:inline">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <button type="submit" class="danger" data-confirm="Delete this carrier?">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($carriers)): ?>
      <tr><td colspan="6" class="text-center py-4 text-muted">No carriers found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ── ADD MODAL ──────────────────────────────────────────── -->
<div class="modal fade" id="addCarrierModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle text-warning me-2"></i>Add Carrier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/admin/carriers" enctype="multipart/form-data">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <input type="hidden" name="action" value="add">
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="adm-form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="adm-form-control" required>
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="adm-form-control" required placeholder="e.g. DHL" style="text-transform:uppercase">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Website URL</label>
            <input type="url" name="website" class="adm-form-control" placeholder="https://...">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Tracking URL <small class="text-muted">(use <code>{tracking}</code> as placeholder)</small></label>
            <input type="text" name="tracking_url" class="adm-form-control" placeholder="https://carrier.com/track?n={tracking}">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Logo <small class="text-muted">(JPG, PNG, WebP — max 5MB)</small></label>
            <input type="file" name="logo" class="adm-form-control" accept="image/*" data-preview="add-logo-preview">
            <div class="mt-2" id="add-logo-preview-wrap" style="display:none">
              <img id="add-logo-preview" src="" style="height:50px;object-fit:contain;border-radius:6px;border:1px solid #e2e8f0;padding:4px;background:#fff">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white fw-bold">
            <i class="fas fa-plus me-1"></i> Add Carrier
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ── EDIT MODAL ──────────────────────────────────────────── -->
<div class="modal fade" id="editCarrierModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-pen text-warning me-2"></i>Edit Carrier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/admin/carriers" enctype="multipart/form-data" id="editCarrierForm">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="adm-form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit-name" class="adm-form-control" required>
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" id="edit-code" class="adm-form-control" required style="text-transform:uppercase">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Website URL</label>
            <input type="url" name="website" id="edit-website" class="adm-form-control" placeholder="https://...">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Tracking URL <small class="text-muted">(use <code>{tracking}</code> as placeholder)</small></label>
            <input type="text" name="tracking_url" id="edit-tracking_url" class="adm-form-control">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Status</label>
            <select name="status" id="edit-status" class="adm-form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="col-12">
            <label class="adm-form-label">Logo</label>
            <!-- Current logo preview -->
            <div id="edit-current-logo-wrap" style="display:none;margin-bottom:.75rem">
              <div style="display:flex;align-items:center;gap:1rem;padding:.75rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                <img id="edit-current-logo" src="" style="height:44px;object-fit:contain;border-radius:4px">
                <div>
                  <div style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.3rem">Current logo</div>
                  <label style="display:flex;align-items:center;gap:.4rem;font-size:.78rem;color:#dc3545;cursor:pointer">
                    <input type="checkbox" name="remove_logo" value="1" id="edit-remove-logo">
                    Remove current logo
                  </label>
                </div>
              </div>
            </div>
            <!-- New logo upload -->
            <input type="file" name="logo" class="adm-form-control" accept="image/*" data-preview="edit-logo-preview">
            <div class="mt-2" id="edit-logo-preview-wrap" style="display:none">
              <img id="edit-logo-preview" src="" style="height:50px;object-fit:contain;border-radius:6px;border:1px solid #e2e8f0;padding:4px;background:#fff">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white fw-bold">
            <i class="fas fa-save me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Populate edit modal
document.querySelectorAll('.btn-edit-carrier').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.getElementById('edit-id').value           = this.dataset.id;
    document.getElementById('edit-name').value         = this.dataset.name;
    document.getElementById('edit-code').value         = this.dataset.code;
    document.getElementById('edit-website').value      = this.dataset.website;
    document.getElementById('edit-tracking_url').value = this.dataset.tracking_url;
    document.getElementById('edit-status').value       = this.dataset.status;
    document.getElementById('edit-remove-logo').checked = false;

    var logoWrap = document.getElementById('edit-current-logo-wrap');
    var logoImg  = document.getElementById('edit-current-logo');
    if (this.dataset.logo) {
      logoImg.src      = this.dataset.logo;
      logoWrap.style.display = 'block';
    } else {
      logoWrap.style.display = 'none';
    }

    // Reset new upload preview
    document.getElementById('edit-logo-preview-wrap').style.display = 'none';
    document.getElementById('edit-logo-preview').src = '';
  });
});

// Image preview for file inputs
document.querySelectorAll('[data-preview]').forEach(function(input) {
  input.addEventListener('change', function() {
    var previewId = this.dataset.preview;
    var preview   = document.getElementById(previewId);
    var wrap      = document.getElementById(previewId + '-wrap');
    if (!preview || !this.files[0]) return;
    var file = this.files[0];
    if (!file.type.startsWith('image/')) return;
    var reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      if (wrap) wrap.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
});
</script>
