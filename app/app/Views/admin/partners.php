<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-handshake me-2 text-warning"></i>Partners</h1></div>
  <button class="btn btn-warning text-white fw-bold" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
    <i class="fas fa-plus me-1"></i> Add Partner
  </button>
</div>
<div class="row g-3">
  <?php foreach ($partners as $p): ?>
  <div class="col-md-3">
    <div class="adm-card p-3 text-center">
      <img src="<?= ASSETS_URL.'/'.Security::e($p['logo']) ?>" style="height:50px;object-fit:contain;margin-bottom:.8rem">
      <div style="font-weight:600;font-size:.9rem"><?= Security::e($p['name']) ?></div>
      <?php if ($p['website']): ?><a href="<?= Security::e($p['website']) ?>" target="_blank" style="font-size:.78rem;color:#888"><?= Security::e($p['website']) ?></a><?php endif; ?>
      <div class="mt-2">
        <form method="POST" action="<?= BASE_URL ?>/admin/partners" style="display:inline">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" data-confirm="Delete?"><i class="fas fa-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div class="modal fade" id="addPartnerModal">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Partner</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="<?= BASE_URL ?>/admin/partners" enctype="multipart/form-data">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <input type="hidden" name="action" value="add">
      <div class="modal-body row g-3">
        <div class="col-12"><label class="adm-form-label">Name</label><input type="text" name="name" class="adm-form-control" required></div>
        <div class="col-12"><label class="adm-form-label">Logo</label><input type="file" name="logo" class="adm-form-control" accept="image/*" required></div>
        <div class="col-12"><label class="adm-form-label">Website</label><input type="url" name="website" class="adm-form-control"></div>
        <div class="col-md-6"><label class="adm-form-label">Sort Order</label><input type="number" name="sort_order" class="adm-form-control" value="0"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning text-white fw-bold">Add</button>
      </div>
    </form>
  </div></div>
</div>
