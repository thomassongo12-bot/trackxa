<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-scroll me-2 text-warning"></i>API Logs</h1></div>
  <a href="<?= BASE_URL ?>/admin/api-keys" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Time</th><th>Website</th><th>Method</th><th>Endpoint</th><th>Code</th><th>Time (ms)</th><th>IP</th></tr></thead>
      <tbody>
      <?php foreach ($logs as $log): ?>
      <?php $codeCls = $log['response_code'] < 300 ? 'success' : ($log['response_code'] < 500 ? 'warning' : 'danger'); ?>
      <tr>
        <td style="font-size:.78rem;color:#888;white-space:nowrap"><?= date('M d H:i:s', strtotime($log['created_at'])) ?></td>
        <td style="font-size:.82rem"><?= Security::e($log['website_name'] ?? '—') ?></td>
        <td><span class="adm-badge adm-badge-<?= $log['method']==='GET'?'info':($log['method']==='POST'?'success':'warning') ?>"><?= $log['method'] ?></span></td>
        <td><code style="font-size:.75rem"><?= Security::e($log['endpoint']) ?></code></td>
        <td><span class="adm-badge adm-badge-<?= $codeCls ?>"><?= $log['response_code'] ?></span></td>
        <td style="font-size:.8rem"><?= round($log['execution_time'], 1) ?></td>
        <td style="font-size:.75rem;color:#888"><?= Security::e($log['ip_address'] ?? '') ?></td>
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
