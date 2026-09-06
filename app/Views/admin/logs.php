<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-list-check me-2 text-warning"></i>Activity Logs</h1></div>
</div>
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Time</th><th class="hide-mobile">Admin</th><th>Action</th><th class="hide-mobile">Model</th><th class="hide-mobile">Details</th><th class="hide-mobile">IP</th></tr></thead>
      <tbody>
      <?php foreach ($logs as $log): ?>
      <tr>
        <td style="font-size:.78rem;white-space:nowrap;color:#888"><?= date('M d H:i:s', strtotime($log['created_at'])) ?></td>
        <td style="font-size:.85rem;font-weight:600"><?= Security::e($log['admin_name'] ?? 'System') ?></td>
        <td><span class="adm-badge adm-badge-primary"><?= Security::e($log['action']) ?></span></td>
        <td style="font-size:.8rem"><?= Security::e($log['model'] ?? '—') ?><?= $log['model_id'] ? ' #'.$log['model_id'] : '' ?></td>
        <td style="font-size:.8rem;color:#555"><?= Security::e(substr($log['details']??'',0,80)) ?></td>
        <td style="font-size:.75rem;color:#888"><?= Security::e($log['ip_address']??'') ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if ($pages > 1): ?>
  <div class="p-3 d-flex justify-content-center">
    <nav><ul class="pagination tx-pagination mb-0">
      <?php for ($p=1;$p<=$pages;$p++): ?>
      <li class="page-item <?= $p===$page?'active':'' ?>"><a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a></li>
      <?php endfor; ?>
    </ul></nav>
  </div>
  <?php endif; ?>
</div>
