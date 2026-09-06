<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-circle-question me-2 text-warning"></i>FAQ Management</h1></div>
  <button class="btn btn-warning text-white fw-bold" data-bs-toggle="modal" data-bs-target="#addFaqModal">
    <i class="fas fa-plus me-1"></i> Add FAQ
  </button>
</div>
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>#</th><th>Question</th><th class="hide-mobile">Lang</th><th class="hide-mobile">Sort</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($faqs as $f): ?>
      <tr>
        <td><?= $f['id'] ?></td>
        <td style="font-size:.88rem"><?= Security::e(substr($f['question'],0,80)) ?>...</td>
        <td><span class="adm-badge adm-badge-info"><?= strtoupper($f['lang']) ?></span></td>
        <td><?= $f['sort_order'] ?></td>
        <td><span class="adm-badge adm-badge-<?= $f['status']?'success':'secondary' ?>"><?= $f['status']?'Active':'Inactive' ?></span></td>
        <td>
          <form method="POST" action="<?= BASE_URL ?>/admin/faq" style="display:inline">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $f['id'] ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" data-confirm="Delete this FAQ?"><i class="fas fa-trash"></i></button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<!-- Add Modal -->
<div class="modal fade" id="addFaqModal">
  <div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add FAQ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="<?= BASE_URL ?>/admin/faq">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <input type="hidden" name="action" value="add">
      <div class="modal-body row g-3">
        <div class="col-12"><label class="adm-form-label">Question</label><input type="text" name="question" class="adm-form-control" required></div>
        <div class="col-12"><label class="adm-form-label">Answer</label><textarea name="answer" class="adm-form-control" rows="4" required></textarea></div>
        <div class="col-md-4"><label class="adm-form-label">Language</label>
          <select name="lang" class="adm-form-control">
            <?php foreach (['en','fr','es','de','it','pt','ar'] as $l): ?><option value="<?= $l ?>"><?= strtoupper($l) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4"><label class="adm-form-label">Category</label><input type="text" name="category" class="adm-form-control" value="general"></div>
        <div class="col-md-4"><label class="adm-form-label">Sort Order</label><input type="number" name="sort_order" class="adm-form-control" value="0"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning text-white fw-bold">Add FAQ</button>
      </div>
    </form>
  </div></div>
</div>
