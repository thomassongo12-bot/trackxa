<?php
$s      = $shipment;
$si     = $statusInfo;
$latest = end($history) ?: null;
$colorMap = ['success'=>'#28a745','danger'=>'#dc3545','warning'=>'#ffc107','primary'=>'#1a3c6e','info'=>'#17a2b8','secondary'=>'#6c757d'];
$color  = $colorMap[$si['color']] ?? '#6c757d';

// All statuses in logical order
$allStatusOrder = [
    'order_received','shipment_created','preparing','picked_up',
    'at_warehouse','in_transit','arrived_airport','departed_airport',
    'customs_clearance','released_customs','out_for_delivery',
    'delivered','delivery_failed','returned','cancelled','delayed','on_hold'
];

// Build progress steps from statuses that actually appear in history + current status
$historyStatuses = array_unique(array_column($history, 'status'));
if (!in_array($s['status'], $historyStatuses)) {
    $historyStatuses[] = $s['status'];
}
// Always include start and end anchors
$anchors = ['order_received'];
if (in_array('delivered', $historyStatuses)) $anchors[] = 'delivered';
elseif (in_array('out_for_delivery', $historyStatuses)) $anchors[] = 'out_for_delivery';
$historyStatuses = array_unique(array_merge($historyStatuses, $anchors));

// Sort by logical order
$progressSteps = array_values(array_filter($allStatusOrder, fn($st) => in_array($st, $historyStatuses)));

// Find current step index
$currentIdx = array_search($s['status'], $progressSteps);
if ($currentIdx === false) $currentIdx = 0;
?>
<!-- Track Result Page -->
<section class="tx-tracking-hero">
  <div class="container">
    <div class="row align-items-center g-3">
      <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div>
            <div style="font-size:.75rem;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.08em"><?= $lang->get('tracking_number') ?></div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <div style="font-size:clamp(1rem,4vw,1.5rem);font-weight:800;color:#fff;letter-spacing:.04em;word-break:break-all">
                <?= Security::e($s['tracking_number']) ?>
              </div>
              <button onclick="navigator.clipboard&&navigator.clipboard.writeText('<?= Security::e($s['tracking_number']) ?>').then(function(){this.innerHTML='<i class=\'fas fa-check\'></i>';var b=this;setTimeout(function(){b.innerHTML='<i class=\'fas fa-copy\'></i>'},1500)}.bind(this))"
                style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:6px;padding:.2rem .5rem;cursor:pointer;font-size:.75rem;flex-shrink:0">
                <i class="fas fa-copy"></i>
              </button>
            </div>
          </div>
          <span class="tx-tracking-badge" style="background:<?= $color ?>22;color:<?= $color ?>;border:1.5px solid <?= $color ?>44;white-space:nowrap">
            <i class="fas <?= $si['icon'] ?? 'fa-circle' ?>"></i>
            <?= Security::e($si['label'] ?? $s['status']) ?>
          </span>
        </div>
      </div>
      <div class="col-12 col-lg-4 text-lg-end">
        <a href="<?= BASE_URL ?>/track" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:none;border-radius:8px;padding:.45rem 1rem;">
          <i class="fas fa-arrow-left me-1"></i> Track Another
        </a>
      </div>
    </div>
  </div>
</section>

<section class="py-4" style="background:#f8f9fa">
  <div class="container">
    <!-- Progress Bar -->
    <div class="tx-card p-3 p-md-4 mb-4">
      <h6 style="font-weight:700;color:var(--tx-primary);margin-bottom:1.2rem;font-size:.9rem"><i class="fas fa-route me-2 text-warning"></i>Shipment Progress</h6>
      <div class="tx-tracking-progress" style="overflow-x:auto;padding-bottom:.5rem">
        <?php foreach ($progressSteps as $idx => $step): ?>
        <?php
          $stepInfo = $statuses[$step] ?? ['label'=>$step,'icon'=>'fa-circle'];
          $isDone = false; $isActive = false;
          if ($s['status'] === 'delivered' && $step !== 'delivery_failed' && $step !== 'returned' && $step !== 'cancelled') {
              $isDone = true;
          } elseif ($idx < $currentIdx) {
              $isDone = true;
          } elseif ($idx === $currentIdx) {
              $isActive = true;
          }
        ?>
        <div class="tx-progress-step <?= $isDone?'completed':($isActive?'active':'') ?>">
          <div class="step-icon"><i class="fas <?= $stepInfo['icon'] ?>"></i></div>
          <div class="step-label"><?= $stepInfo['label'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="row g-3 g-md-4">
      <!-- Timeline -->
      <div class="col-12 col-lg-8 order-2 order-lg-1">
        <div class="tx-card p-3 p-md-4">
          <h6 style="font-weight:700;color:var(--tx-primary);margin-bottom:1.2rem"><i class="fas fa-timeline me-2 text-warning"></i><?= $lang->get('tracking_history') ?></h6>
          <?php if (empty($history)): ?>
          <p class="text-muted" style="font-size:.9rem">No tracking updates yet.</p>
          <?php else: ?>
          <ul class="tx-timeline">
            <?php foreach (array_reverse($history) as $idx => $h): ?>
            <?php
              $hStatus = $statuses[$h['status']] ?? ['label'=>$h['status'],'color'=>'secondary','icon'=>'fa-circle'];
              $dotClass = match($hStatus['color']) { 'success'=>'success','danger'=>'danger','warning'=>'warning','primary','info'=>'active',default=>'default' };
            ?>
            <li>
              <div class="tx-timeline-dot <?= $dotClass ?>"><i class="fas <?= $hStatus['icon'] ?>"></i></div>
              <div class="tx-timeline-content">
                <div class="title"><?= Security::e($hStatus['label']) ?></div>
                <div class="meta">
                  <span><i class="far fa-calendar me-1"></i><?= date('M d, Y', strtotime($h['occurred_at'])) ?></span>
                  <span><i class="far fa-clock me-1"></i><?= date('H:i', strtotime($h['occurred_at'])) ?></span>
                  <?php if (!empty($h['location'])): ?><span><i class="fas fa-map-marker-alt me-1"></i><?= Security::e($h['location']) ?></span><?php endif; ?>
                </div>
                <?php if (!empty($h['description'])): ?><div class="desc"><?= Security::e($h['description']) ?></div><?php endif; ?>
                <?php if (!empty($h['operator_notes'])): ?><div class="notes"><i class="fas fa-sticky-note me-1"></i><?= Security::e($h['operator_notes']) ?></div><?php endif; ?>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>

      <!-- Shipment Info -->
      <div class="col-12 col-lg-4 order-1 order-lg-2">
        <!-- Status Card -->
        <div class="tx-card p-3 p-md-4 mb-3" style="border-top:4px solid <?= $color ?>">
          <h6 style="font-weight:700;color:var(--tx-primary);margin-bottom:.8rem;font-size:.9rem"><i class="fas fa-circle-info me-2 text-warning"></i>Current Status</h6>
          <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:<?= $color ?>12">
            <i class="fas <?= $si['icon'] ?>" style="font-size:1.3rem;color:<?= $color ?>"></i>
            <div>
              <div style="font-weight:700;color:<?= $color ?>;font-size:.9rem"><?= Security::e($si['label']) ?></div>
              <?php if ($latest && !empty($latest['location'])): ?>
              <div style="font-size:.75rem;color:#64748b"><i class="fas fa-map-marker-alt me-1"></i><?= Security::e($latest['location']) ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Details -->
        <div class="tx-card p-3 p-md-4 mb-3">
          <h6 style="font-weight:700;color:var(--tx-primary);margin-bottom:.8rem;font-size:.9rem"><i class="fas fa-box me-2 text-warning"></i><?= $lang->get('shipment_details') ?></h6>
          <?php $details = [
            ['icon'=>'fa-location-dot',    'label'=>$lang->get('origin'),      'val'=>trim(($s['origin_city']??'').', '.($s['origin_country_name']??''), ', ')],
            ['icon'=>'fa-flag-checkered',  'label'=>$lang->get('destination'), 'val'=>trim(($s['destination_city']??'').', '.($s['destination_country_name']??''), ', ')],
            ['icon'=>'fa-calendar',        'label'=>$lang->get('shipping_date'),'val'=>$s['shipping_date']?date('M d, Y',strtotime($s['shipping_date'])):'—'],
            ['icon'=>'fa-calendar-check',  'label'=>$lang->get('estimated_delivery'),'val'=>$s['estimated_delivery']?date('M d, Y',strtotime($s['estimated_delivery'])):'—'],
            ['icon'=>'fa-truck',           'label'=>$lang->get('carrier'),     'val'=>$s['carrier_name']??'—'],
            ['icon'=>'fa-weight-hanging',  'label'=>$lang->get('weight'),      'val'=>$s['weight']?$s['weight'].' '.($s['weight_unit']??'kg'):'—'],
            ['icon'=>'fa-box',             'label'=>$lang->get('package_type'),'val'=>$s['package_type_name']??'—'],
            ['icon'=>'fa-shipping-fast',   'label'=>$lang->get('shipping_method'),'val'=>$s['shipping_method_name']??'—'],
          ]; foreach ($details as $d): if(!$d['val']||$d['val']==='—') continue; ?>
          <div class="d-flex justify-content-between gap-2 py-2" style="border-bottom:1px solid #f1f5f9;font-size:.82rem">
            <span style="color:#64748b;flex-shrink:0"><i class="fas <?= $d['icon'] ?> me-1 text-warning" style="width:14px"></i><?= $d['label'] ?></span>
            <span style="font-weight:600;text-align:right"><?= Security::e($d['val']) ?></span>
          </div>
          <?php endforeach; ?>
          <?php if ($s['status']==='delivered' && !empty($s['actual_delivery'])): ?>
          <div class="mt-3 p-3 rounded-3 text-center" style="background:#d4edda">
            <i class="fas fa-circle-check text-success" style="font-size:1.3rem"></i>
            <div style="font-weight:700;color:#155724;margin:.3rem 0;font-size:.9rem">Package Delivered!</div>
            <div style="font-size:.78rem;color:#155724"><?= date('M d, Y H:i', strtotime($s['actual_delivery'])) ?></div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Special Instructions -->
        <?php if (!empty($s['special_instructions'])): ?>
        <div class="tx-card p-3">
          <h6 style="font-weight:700;color:var(--tx-primary);margin-bottom:.6rem;font-size:.85rem"><i class="fas fa-clipboard-list me-1 text-warning"></i>Special Instructions</h6>
          <p style="font-size:.83rem;color:#555;margin:0"><?= Security::e($s['special_instructions']) ?></p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
