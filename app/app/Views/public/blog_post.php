<article>
  <!-- Post Header -->
  <section style="background:linear-gradient(135deg,var(--tx-primary-dark),var(--tx-primary));padding:70px 0 50px">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-white text-center">
          <?php if (!empty($post['category_name'])): ?>
          <span class="tx-blog-category mb-3"><?= Security::e($post['category_name']) ?></span>
          <?php endif; ?>
          <h1 style="font-weight:800;font-size:clamp(1.5rem,3vw,2.2rem);line-height:1.3;margin-top:.5rem"><?= Security::e($post['title']) ?></h1>
          <div class="d-flex align-items-center justify-content-center gap-3 mt-3 flex-wrap" style="font-size:.85rem;color:rgba(255,255,255,.7)">
            <span><i class="far fa-calendar me-1"></i><?= date('F d, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
            <?php if (!empty($post['author_name'])): ?><span><i class="fas fa-user me-1"></i><?= Security::e($post['author_name']) ?></span><?php endif; ?>
            <span><i class="fas fa-eye me-1"></i><?= number_format($post['views']) ?> views</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Content -->
  <section class="tx-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <?php if (!empty($post['featured_image'])): ?>
          <img src="<?= ASSETS_URL.'/'.Security::e($post['featured_image']) ?>" class="w-100 mb-4 rounded-3" style="max-height:450px;object-fit:cover" alt="<?= Security::e($post['title']) ?>">
          <?php endif; ?>
          <div style="line-height:1.85;color:#374151;font-size:1.02rem">
            <?= $post['content'] /* HTML content, sanitized on input */ ?>
          </div>
          <?php if (!empty($post['tags'])): ?>
          <div class="mt-4 pt-3" style="border-top:1px solid var(--tx-border)">
            <i class="fas fa-tags me-2 text-warning"></i>
            <?php foreach (explode(',', $post['tags']) as $tag): ?>
            <span class="tx-blog-category me-1"><?= Security::e(trim($tag)) ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <!-- Share -->
          <div class="mt-4 p-4 rounded-3" style="background:#f8f9fa;border:1px solid var(--tx-border)">
            <strong style="font-size:.9rem">Share this article:</strong>
            <div class="d-flex gap-2 mt-2">
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL.'/blog/'.$post['slug']) ?>" target="_blank" class="btn btn-sm" style="background:#1877f2;color:#fff;border:none"><i class="fab fa-facebook-f me-1"></i>Facebook</a>
              <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL.'/blog/'.$post['slug']) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" class="btn btn-sm" style="background:#1da1f2;color:#fff;border:none"><i class="fab fa-twitter me-1"></i>Twitter</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Related -->
      <?php if (!empty($related)): ?>
      <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
          <h4 style="font-weight:700;color:var(--tx-primary);margin-bottom:1.5rem">Related Articles</h4>
          <div class="row g-3">
            <?php foreach ($related as $r): ?>
            <div class="col-md-4">
              <div class="tx-card p-3">
                <div style="font-weight:600;font-size:.9rem"><a href="<?= BASE_URL ?>/blog/<?= Security::e($r['slug']) ?>" style="color:var(--tx-primary)"><?= Security::e($r['title']) ?></a></div>
                <div class="tx-blog-meta mt-1"><?= date('M d, Y', strtotime($r['published_at']??$r['created_at'])) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>
</article>
