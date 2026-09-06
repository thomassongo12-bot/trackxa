<?php $isEdit = !empty($post); $p = $post ?? []; ?>
<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-pen me-2 text-warning"></i><?= $isEdit ? 'Edit Post' : 'New Post' ?></h1></div>
  <a href="<?= BASE_URL ?>/admin/blog" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>
<form action="<?= BASE_URL ?>/admin/blog/<?= $isEdit ? $p['id'].'/edit' : 'create' ?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="adm-form-section">
        <div class="mb-3">
          <label class="adm-form-label">Post Title <span class="text-danger">*</span></label>
          <input type="text" name="title" id="adm-post-title" class="adm-form-control" value="<?= Security::e($p['title']??'') ?>" required>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">URL Slug</label>
          <input type="text" name="slug" id="adm-post-slug" class="adm-form-control" value="<?= Security::e($p['slug']??'') ?>" placeholder="auto-generated">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Excerpt</label>
          <textarea name="excerpt" class="adm-form-control" rows="3"><?= Security::e($p['excerpt']??'') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Content <span class="text-danger">*</span></label>
          <textarea name="content" id="adm-content" class="adm-form-control" rows="15" style="font-family:monospace"><?= htmlspecialchars($p['content']??'', ENT_QUOTES) ?></textarea>
          <div style="font-size:.75rem;color:#888;margin-top:.3rem"><i class="fas fa-info-circle me-1"></i>HTML is allowed. Use a rich text editor by including TinyMCE via CDN.</div>
        </div>
      </div>
      <!-- SEO -->
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-magnifying-glass-chart text-warning"></i> SEO</div>
        <div class="mb-3">
          <label class="adm-form-label">Meta Title</label>
          <input type="text" name="meta_title" class="adm-form-control" value="<?= Security::e($p['meta_title']??'') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Meta Description</label>
          <textarea name="meta_description" class="adm-form-control" rows="2"><?= Security::e($p['meta_description']??'') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Keywords</label>
          <input type="text" name="meta_keywords" class="adm-form-control" value="<?= Security::e($p['meta_keywords']??'') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Tags (comma separated)</label>
          <input type="text" name="tags" class="adm-form-control" value="<?= Security::e($p['tags']??'') ?>">
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-gear text-warning"></i> Publish</div>
        <div class="mb-3">
          <label class="adm-form-label">Status</label>
          <select name="status" class="adm-form-control">
            <option value="draft" <?= ($p['status']??'draft')==='draft'?'selected':'' ?>>Draft</option>
            <option value="published" <?= ($p['status']??'')==='published'?'selected':'' ?>>Published</option>
            <option value="scheduled" <?= ($p['status']??'')==='scheduled'?'selected':'' ?>>Scheduled</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Publish Date</label>
          <input type="datetime-local" name="published_at" class="adm-form-control" value="<?= Security::e(str_replace(' ','T',substr($p['published_at']??'',0,16))) ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Category</label>
          <select name="category_id" class="adm-form-control">
            <option value="">— No Category —</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($p['category_id']??'')==$cat['id']?'selected':'' ?>><?= Security::e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Featured Image</label>
          <?php if (!empty($p['featured_image'])): ?>
          <img src="<?= ASSETS_URL.'/'.Security::e($p['featured_image']) ?>" style="width:100%;border-radius:8px;margin-bottom:.5rem">
          <?php endif; ?>
          <input type="file" name="featured_image" class="adm-form-control" accept="image/*" data-preview="feat-img-preview">
          <img id="feat-img-preview" src="" style="display:none;width:100%;border-radius:8px;margin-top:.5rem">
        </div>
        <button type="submit" class="btn btn-warning text-white fw-bold w-100">
          <i class="fas fa-save me-1"></i> <?= $isEdit ? 'Save Changes' : 'Publish Post' ?>
        </button>
      </div>
    </div>
  </div>
</form>
