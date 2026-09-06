<section style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));padding:70px 0 50px">
  <div class="container text-center text-white">
    <h1 style="font-weight:800"><i class="fas fa-newspaper me-2" style="color:var(--tx-accent)"></i><?= isset($currentCat) ? Security::e($currentCat['name']) : $lang->get('latest_news') ?></h1>
    <p style="color:rgba(255,255,255,.75)">Shipping guides, tracking tips, and logistics insights</p>
  </div>
</section>
<section class="tx-section">
  <div class="container">
    <?php if (empty($posts)): ?>
    <div class="text-center py-5"><i class="fas fa-newspaper" style="font-size:3rem;color:#ccc"></i><p class="text-muted mt-3">No posts yet.</p></div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($posts as $post): ?>
      <div class="col-lg-4 col-md-6">
        <div class="tx-card tx-blog-card h-100">
          <?php if (!empty($post['featured_image'])): ?>
          <img src="<?= ASSETS_URL.'/'.Security::e($post['featured_image']) ?>" class="card-img-top" alt="<?= Security::e($post['title']) ?>" loading="lazy">
          <?php else: ?>
          <div style="height:220px;background:linear-gradient(135deg,var(--tx-primary),#2563eb);display:flex;align-items:center;justify-content:center;"><i class="fas fa-newspaper" style="font-size:3rem;color:rgba(255,255,255,.2)"></i></div>
          <?php endif; ?>
          <div class="p-4">
            <?php if (!empty($post['category_name'])): ?>
            <span class="tx-blog-category"><?= Security::e($post['category_name']) ?></span>
            <?php endif; ?>
            <h5 class="mt-2 mb-2" style="font-weight:700;font-size:1rem;line-height:1.4"><a href="<?= BASE_URL ?>/blog/<?= Security::e($post['slug']) ?>" style="color:var(--tx-primary)"><?= Security::e($post['title']) ?></a></h5>
            <p class="tx-blog-meta mb-2"><i class="far fa-calendar me-1"></i><?= date('M d, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?> &bull; <i class="fas fa-eye me-1"></i><?= number_format($post['views']) ?></p>
            <?php if ($post['excerpt']): ?><p style="font-size:.88rem;color:#64748b;margin:0"><?= Security::e(substr($post['excerpt'],0,100)) ?>...</p><?php endif; ?>
            <a href="<?= BASE_URL ?>/blog/<?= Security::e($post['slug']) ?>" class="tx-btn-primary mt-3" style="font-size:.82rem;padding:.4rem 1rem"><?= $lang->get('read_more') ?> <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if ($pagination['pages'] > 1): ?>
    <div class="d-flex justify-content-center mt-5">
      <nav><ul class="pagination tx-pagination mb-0">
        <?php for ($p=1;$p<=$pagination['pages'];$p++): ?>
        <li class="page-item <?= $p===$pagination['page']?'active':'' ?>"><a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a></li>
        <?php endfor; ?>
      </ul></nav>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
