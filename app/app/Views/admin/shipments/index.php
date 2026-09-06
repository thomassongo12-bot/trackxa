<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-boxes-stacked me-2 text-warning"></i>Shipments</h1>
    <p class="adm-page-subtitle">Manage all shipments</p>
  </div>
  <a href="<?= BASE_URL ?>/admin/shipments/create" class="btn btn-warning text-white fw-bold">
    <i class="fas fa-plus me-1"></i> New Shipment
  </a>
</div>

<!-- Filters -->
<div class="adm-card mb-3">
  <div class="adm-card-body py-3">
    <form class="row g-2 align-items-end" method="GET">
      <div class="col-md-5">
        <input type="text" name="q" class="adm-form-control" placeholder="Search tracking #, recipient, reference..." value="<?= Security::e($q ?? '') ?>">
      </div>
      <div class="col-md-4">
        <select name="status" class="adm-form-control">
          <option value="">All Statuses</option>
          <?php foreach ($statuses as $key => $info): ?>
          <option value="<?= $key ?>" <?= ($status??'')===$key?'selected':'' ?>><?= $info['label'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-search me-1"></i> Filter</button>
        <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
      </div>
    </form>
  </div>
</div>

<!-- Table -->
<div class="adm-card">
  <div class="adm-card-header">
    <h5 class="adm-card-title"><i class="fas fa-list text-warning"></i> <?= number_format($result['total']) ?> Shipments</h5>
    <span style="font-size:.8rem;color:#888">Page <?= $result['page'] ?> of <?= max(1,$result['pages']) ?></span>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr>
        <th><input type="checkbox" id="adm-select-all"></th>
        <th>Tracking #</th><th>Recipient</th><th>Origin → Dest</th>
        <th>Status</th><th>Est. Delivery</th><th>Website</th><th>Created</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php if (empty($result['data'])): ?>
      <tr><td colspan="9" class="text-center py-4 text-muted">No shipments found.</td></tr>
      <?php endif; ?>
      <?php foreach ($result['data'] as $row): ?>
      <?php $si = $statuses[$row['status']] ?? ['label'=>$row['status'],'color'=>'secondary','icon'=>'fa-circle']; ?>
      <tr>
        <td><input type="checkbox" class="adm-row-check" value="<?= $row['id'] ?>"></td>
        <td>
          <code style="font-size:.82rem;cursor:pointer" data-copy="<?= Security::e($row['tracking_number']) ?>" title="Click to copy">
            <?= Security::e($row['tracking_number']) ?>
          </code>
          <?php if ($row['reference_number']): ?><br><small class="text-muted"><?= Security::e($row['reference_number']) ?></small><?php endif; ?>
        </td>
        <td>
          <div style="font-weight:600;font-size:.88rem"><?= Security::e($row['recipient_name']) ?></div>
          <?php if ($row['recipient_city']): ?><small class="text-muted"><?= Security::e($row['recipient_city']) ?></small><?php endif; ?>
        </td>
        <td style="font-size:.82rem">
          <?= Security::e($row['origin_city'] ?? '?') ?> <i class="fas fa-arrow-right mx-1 text-muted" style="font-size:.7rem"></i> <?= Security::e($row['destination_city'] ?? '?') ?>
        </td>
        <td><span class="adm-badge adm-badge-<?= $si['color'] ?>"><i class="fas <?= $si['icon'] ?> me-1"></i><?= $si['label'] ?></span></td>
        <td style="font-size:.82rem"><?= $row['estimated_delivery'] ? date('M d, Y', strtotime($row['estimated_delivery'])) : '—' ?></td>
        <td style="font-size:.8rem;color:#888"><?= Security::e($row['website_name'] ?? '—') ?></td>
        <td style="font-size:.78rem;color:#888"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
        <td>
          <div class="actions">
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $row['id'] ?>/view" title="View"><i class="fas fa-eye"></i></a>
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $row['id'] ?>/edit" title="Edit"><i class="fas fa-pen"></i></a>
            <form method="POST" action="<?= BASE_URL ?>/admin/shipments/<?= $row['id'] ?>/archive" style="display:inline">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
              <button type="submit" title="Archive" data-confirm="Archive this shipment?"><i class="fas fa-archive"></i></button>
            </form>
            <form method="POST" action="<?= BASE_URL ?>/admin/shipments/<?= $row['id'] ?>/delete" style="display:inline">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
              <button type="submit" class="danger" title="Delete" data-confirm="Permanently delete this shipment?"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- Pagination -->
  <?php if ($result['pages'] > 1): ?>
  <div class="p-3 d-flex justify-content-center">
    <nav><ul class="pagination tx-pagination mb-0">
      <?php for ($p = 1; $p <= $result['pages']; $p++): ?>
      <li class="page-item <?= $p===$result['page']?'active':'' ?>">
        <a class="page-link" href="?page=<?= $p ?>&q=<?= urlencode($q??'') ?>&status=<?= urlencode($status??'') ?>"><?= $p ?></a>
      </li>
      <?php endfor; ?>
    </ul></nav>
  </div>
  <?php endif; ?>
</div>
