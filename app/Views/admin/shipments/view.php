<?php $s = $shipment; $si = $statusInfo; ?>
<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-eye me-2 text-warning"></i><?= Security::e($s['tracking_number']) ?></h1>
    <p class="adm-page-subtitle">Shipment details</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/admin/shipments/<?= $s['id'] ?>/edit" class="btn btn-warning text-white fw-bold"><i class="fas fa-pen me-1"></i> Edit</a>
    <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="adm-card p-4 mb-3">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 style="font-weight:800;color:var(--adm-primary)"><?= Security::e($s['tracking_number']) ?></h5>
        <span class="adm-badge adm-badge-<?= $si['color'] ?? 'secondary' ?>">
          <i class="fas <?= $si['icon'] ?? 'fa-circle' ?> me-1"></i><?= Security::e($si['label'] ?? $s['status']) ?>
        </span>
      </div>
      <div class="row g-3">
        <?php $fields = [
          'Recipient'   => $s['recipient_name'].' | '.$s['recipient_city'],
          'Sender'      => $s['sender_name'] ?? '—',
          'Origin'      => ($s['origin_city'] ?? '—').', '.($s['origin_country_name'] ?? ''),
          'Destination' => ($s['destination_city'] ?? '—').', '.($s['destination_country_name'] ?? ''),
          'Carrier'     => $s['carrier_name'] ?? '—',
          'Method'      => $s['shipping_method_name'] ?? '—',
          'Weight'      => $s['weight'] ? $s['weight'].' '.($s['weight_unit']??'kg') : '—',
          'Package'     => $s['package_type_name'] ?? '—',
          'Ship Date'   => $s['shipping_date'] ? date('M d, Y', strtotime($s['shipping_date'])) : '—',
          'Est. Delivery'=> $s['estimated_delivery'] ? date('M d, Y', strtotime($s['estimated_delivery'])) : '—',
          'Website'     => $s['website_name'] ?? '—',
          'Reference'   => $s['reference_number'] ?? '—',
        ]; foreach ($fields as $lbl => $val): ?>
        <div class="col-md-6">
          <div style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.06em"><?= $lbl ?></div>
          <div style="font-weight:600;font-size:.88rem"><?= Security::e($val) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Timeline -->
    <div class="adm-card p-4">
      <h6 style="font-weight:700;color:var(--adm-primary);margin-bottom:1rem"><i class="fas fa-timeline me-2 text-warning"></i>Tracking Timeline</h6>
      <ul class="adm-timeline">
        <?php foreach (array_reverse($history) as $h): ?>
        <?php $hi = ShipmentModel::STATUSES[$h['status']] ?? ['label'=>$h['status'],'color'=>'secondary','icon'=>'fa-circle']; ?>
        <li class="adm-timeline-item">
          <div class="adm-timeline-dot adm-badge-<?= $hi['color'] ?>" style="border-color:currentColor"><i class="fas <?= $hi['icon'] ?>"></i></div>
          <div class="adm-timeline-body">
            <div class="tl-title"><?= Security::e($hi['label']) ?></div>
            <div class="tl-meta">
              <span><?= date('M d, Y H:i', strtotime($h['occurred_at'])) ?></span>
              <?php if ($h['location']): ?><span><i class="fas fa-map-marker-alt me-1"></i><?= Security::e($h['location']) ?></span><?php endif; ?>
            </div>
            <?php if ($h['description']): ?><div class="tl-desc"><?= Security::e($h['description']) ?></div><?php endif; ?>
            <?php if ($h['operator_notes']): ?><div class="tl-notes"><?= Security::e($h['operator_notes']) ?></div><?php endif; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="adm-card p-4 mb-3">
      <h6 style="font-weight:700;color:var(--adm-primary);margin-bottom:1rem"><i class="fas fa-qrcode me-2 text-warning"></i>Tracking Link</h6>
      <a href="<?= BASE_URL ?>/track/<?= Security::e($s['tracking_number']) ?>" target="_blank" class="btn btn-outline-primary w-100 mb-2">
        <i class="fas fa-external-link-alt me-1"></i> Open Tracking Page
      </a>
      <button class="btn btn-outline-secondary w-100" data-copy="<?= BASE_URL ?>/track/<?= Security::e($s['tracking_number']) ?>">
        <i class="fas fa-copy me-1"></i> Copy Link
      </button>
    </div>
    <?php if ($s['description']): ?>
    <div class="adm-card p-4">
      <h6 style="font-weight:700;color:var(--adm-primary);margin-bottom:.7rem">Description</h6>
      <p style="font-size:.88rem;color:#555;margin:0"><?= Security::e($s['description']) ?></p>
    </div>
    <?php endif; ?>
  </div>
</div>
