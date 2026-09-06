<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-newspaper me-2 text-warning"></i>Blog Posts</h1>
  </div>
  <a href="<?= BASE_URL ?>/admin/blog/create" class="btn btn-warning text-white fw-bold"><i class="fas fa-plus me-1"></i> New Post</a>
</div>
<div class="adm-card">
  <div class="adm-card-header">
    <h5 class="adm-card-title">Posts (<?= $result['total'] ?>)</h5>
    <div class="d-flex gap-2 flex-wrap">
      <?php foreach ($categories as $cat): ?>
      <a href="?cat=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-secondary"><?= Security::e($cat['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Views</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($result['data'])): ?>
      <tr><td colspan="6" class="text-center py-4 text-muted">No posts yet.</td></tr>
      <?php endif; ?>
      <?php foreach ($result['data'] as $post): ?>
      <tr>
        <td>
          <div style="font-weight:600;font-size:.9rem"><?= Security::e($post['title']) ?></div>
          <code style="font-size:.72rem;color:#888">/blog/<?= Security::e($post['slug']) ?></code>
        </td>
        <td style="font-size:.82rem"><?= Security::e($post['category_id'] ?? '—') ?></td>
        <td>
          <?php $sc = $post['status']==='published'?'success':($post['status']==='scheduled'?'warning':'secondary'); ?>
          <span class="adm-badge adm-badge-<?= $sc ?>"><?= ucfirst($post['status']) ?></span>
        </td>
        <td><?= number_format($post['views']) ?></td>
        <td style="font-size:.78rem;color:#888"><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
        <td>
          <div class="actions">
            <a href="<?= BASE_URL ?>/blog/<?= Security::e($post['slug']) ?>" target="_blank" title="View"><i class="fas fa-eye"></i></a>
            <a href="<?= BASE_URL ?>/admin/blog/<?= $post['id'] ?>/edit" title="Edit"><i class="fas fa-pen"></i></a>
            <form method="POST" action="<?= BASE_URL ?>/admin/blog/<?= $post['id'] ?>/delete" style="display:inline">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
              <button type="submit" class="danger" data-confirm="Delete this post?"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
