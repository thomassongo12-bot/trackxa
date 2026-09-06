<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-earth-africa me-2 text-warning"></i>Countries</h1></div>
</div>
<div class="adm-card">
  <div class="adm-card-header">
    <h5 class="adm-card-title">All Countries (<?= count($countries) ?>)</h5>
    <input type="text" id="adm-table-search" class="adm-form-control" style="max-width:250px" placeholder="Search...">
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Name</th><th>Code</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach ($countries as $c): ?>
      <tr>
        <td style="font-weight:600"><?= Security::e($c['name']) ?></td>
        <td><code><?= Security::e($c['code']) ?></code></td>
        <td><span class="adm-badge adm-badge-<?= $c['status']?'success':'secondary' ?>"><?= $c['status']?'Active':'Inactive' ?></span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
