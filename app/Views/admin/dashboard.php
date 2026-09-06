<?php
// Prepare chart data
$visitorLabels = array_column($visitorStats ?? [], 'day');
$visitorValues = array_column($visitorStats ?? [], 'visits');
$statusLabels  = array_column($byStatus ?? [], 'status');
$statusValues  = array_column($byStatus ?? [], 'cnt');
$statusLabels  = array_map(fn($s) => ShipmentModel::STATUSES[$s]['label'] ?? $s, $statusLabels);
?>
<script>
window.VISITOR_DATA = {labels:<?= json_encode($visitorLabels) ?>, values:<?= json_encode($visitorValues) ?>};
window.STATUS_DATA  = {labels:<?= json_encode($statusLabels)  ?>, values:<?= json_encode($statusValues)  ?>};
</script>

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-chart-pie me-2 text-warning"></i>Dashboard</h1>
    <p class="adm-page-subtitle">Welcome back, <?= Security::e($session->get('admin_name','Admin')) ?>!</p>
  </div>
  <a href="<?= BASE_URL ?>/admin/shipments/create" class="btn btn-warning text-white fw-bold">
    <i class="fas fa-plus me-1"></i> New Shipment
  </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <?php $cards = [
    ['value'=>number_format((int)($stats['total']??0)), 'label'=>'Total Shipments',    'icon'=>'fa-boxes-stacked', 'bg'=>'linear-gradient(135deg,#1a3c6e,#2563eb)', 'change'=>'+'.((int)($stats['today']??0)).' today'],
    ['value'=>number_format((int)($stats['today']??0)), 'label'=>"Today's Shipments",  'icon'=>'fa-calendar-day',  'bg'=>'linear-gradient(135deg,#e8a020,#f97316)', 'change'=>'Created today'],
    ['value'=>number_format((int)($stats['delivered']??0)), 'label'=>'Delivered',      'icon'=>'fa-circle-check',  'bg'=>'linear-gradient(135deg,#28a745,#20c997)', 'change'=>'Successfully'],
    ['value'=>number_format((int)($stats['in_transit']??0)), 'label'=>'In Transit',    'icon'=>'fa-truck',         'bg'=>'linear-gradient(135deg,#17a2b8,#6610f2)', 'change'=>'Moving'],
    ['value'=>number_format((int)($stats['pending']??0)), 'label'=>'Pending',          'icon'=>'fa-clock',         'bg'=>'linear-gradient(135deg,#6c757d,#495057)', 'change'=>'Awaiting'],
    ['value'=>number_format((int)($countryCount??0)), 'label'=>'Countries',             'icon'=>'fa-earth-americas','bg'=>'linear-gradient(135deg,#dc3545,#c0392b)', 'change'=>'Worldwide'],
  ]; foreach ($cards as $c): ?>
  <div class="col-xl-2 col-md-4 col-6">
    <div class="adm-stat-card">
      <div class="info">
        <div class="value"><?= $c['value'] ?></div>
        <div class="label"><?= $c['label'] ?></div>
        <div class="change" style="color:#28a745"><i class="fas fa-arrow-up me-1"></i><?= $c['change'] ?></div>
      </div>
      <div class="icon-wrap" style="background:<?= $c['bg'] ?>; color:#fff">
        <i class="fas <?= $c['icon'] ?>"></i>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="adm-card">
      <div class="adm-card-header">
        <h5 class="adm-card-title"><i class="fas fa-chart-line text-warning"></i> Visitor Traffic (Last 7 Days)</h5>
      </div>
      <div class="adm-card-body">
        <div class="adm-chart-wrap"><canvas id="chart-visitors"></canvas></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="adm-card">
      <div class="adm-card-header">
        <h5 class="adm-card-title"><i class="fas fa-chart-donut text-warning"></i> By Status</h5>
      </div>
      <div class="adm-card-body">
        <div class="adm-chart-wrap"><canvas id="chart-statuses"></canvas></div>
      </div>
    </div>
  </div>
</div>

<!-- Latest Shipments + Activity -->
<div class="row g-3">
  <div class="col-lg-8">
    <div class="adm-card">
      <div class="adm-card-header">
        <h5 class="adm-card-title"><i class="fas fa-boxes-stacked text-warning"></i> Latest Shipments</h5>
        <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Tracking #</th><th>Recipient</th><th>Status</th><th class="hide-mobile">Date</th><th>Action</th></tr></thead>
          <tbody>
          <?php foreach ($latest ?? [] as $row): ?>
          <?php $si = ShipmentModel::STATUSES[$row['status']] ?? ['label'=>$row['status'],'color'=>'secondary','icon'=>'fa-circle']; ?>
          <tr>
            <td><code style="font-size:.82rem"><?= Security::e($row['tracking_number']) ?></code></td>
            <td><?= Security::e($row['recipient_name']) ?></td>
            <td><span class="adm-badge adm-badge-<?= $si['color'] ?>"><i class="fas <?= $si['icon'] ?> me-1"></i><?= $si['label'] ?></span></td>
            <td style="font-size:.8rem;color:#888"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
            <td><a href="<?= BASE_URL ?>/admin/shipments/<?= $row['id'] ?>/view" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="fas fa-eye"></i></a></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="adm-card">
      <div class="adm-card-header">
        <h5 class="adm-card-title"><i class="fas fa-list-check text-warning"></i> Recent Activity</h5>
      </div>
      <div class="adm-card-body" style="max-height:340px;overflow-y:auto">
        <?php foreach ($recentLogs ?? [] as $log): ?>
        <div class="d-flex gap-2 mb-3">
          <div style="width:8px;height:8px;background:var(--adm-accent);border-radius:50%;flex-shrink:0;margin-top:.4rem"></div>
          <div style="flex:1">
            <div style="font-size:.82rem;font-weight:600;color:var(--adm-primary)"><?= Security::e($log['action']) ?></div>
            <div style="font-size:.75rem;color:#888"><?= Security::e($log['admin_name'] ?? 'System') ?> · <?= date('M d H:i', strtotime($log['created_at'])) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- API Stats -->
    <div class="adm-card mt-3">
      <div class="adm-card-header"><h5 class="adm-card-title"><i class="fas fa-plug text-warning"></i> API (This Month)</h5></div>
      <div class="adm-card-body">
        <div class="d-flex justify-content-between mb-2">
          <span style="font-size:.85rem">Total Calls</span>
          <strong><?= number_format((int)($apiStats['total'] ?? 0)) ?></strong>
        </div>
        <div class="d-flex justify-content-between">
          <span style="font-size:.85rem">Successful</span>
          <strong style="color:#28a745"><?= number_format((int)($apiStats['success'] ?? 0)) ?></strong>
        </div>
      </div>
    </div>
  </div>
</div>
