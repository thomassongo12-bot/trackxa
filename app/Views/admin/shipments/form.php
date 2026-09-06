<?php $isEdit = !empty($shipment); $s = $shipment ?? []; ?>
<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-<?= $isEdit?'pen':'plus-circle' ?> me-2 text-warning"></i><?= $isEdit ? 'Edit Shipment' : 'Create Shipment' ?></h1>
    <?php if ($isEdit): ?><p class="adm-page-subtitle">Tracking: <?= Security::e($s['tracking_number']) ?></p><?php endif; ?>
  </div>
  <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<form action="<?= BASE_URL ?>/admin/shipments/<?= $isEdit ? $s['id'].'/edit' : 'create' ?>" method="POST" class="row g-3">
  <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">

  <div class="col-lg-8">
    <!-- Recipient -->
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-user text-warning"></i> Recipient Information</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="adm-form-label">Full Name <span class="text-danger">*</span></label>
          <input type="text" name="recipient_name" class="adm-form-control" value="<?= Security::e($s['recipient_name']??'') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Phone</label>
          <input type="text" name="recipient_phone" class="adm-form-control" value="<?= Security::e($s['recipient_phone']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Email</label>
          <input type="email" name="recipient_email" class="adm-form-control" value="<?= Security::e($s['recipient_email']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">City <span class="text-danger">*</span></label>
          <input type="text" name="recipient_city" class="adm-form-control" value="<?= Security::e($s['recipient_city']??'') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Country</label>
          <select name="recipient_country_id" class="adm-form-control">
            <option value="">— Select Country —</option>
            <?php foreach ($countries as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($s['recipient_country_id']??'')==$c['id']?'selected':'' ?>><?= Security::e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Postal Code</label>
          <input type="text" name="recipient_postal_code" class="adm-form-control" value="<?= Security::e($s['recipient_postal_code']??'') ?>">
        </div>
        <div class="col-12">
          <label class="adm-form-label">Address <span class="text-danger">*</span></label>
          <input type="text" name="recipient_address" class="adm-form-control" value="<?= Security::e($s['recipient_address']??'') ?>" required>
        </div>
      </div>
    </div>

    <!-- Sender -->
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-user-tie text-warning"></i> Sender Information</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="adm-form-label">Sender Name</label>
          <input type="text" name="sender_name" class="adm-form-control" value="<?= Security::e($s['sender_name']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Sender Phone</label>
          <input type="text" name="sender_phone" class="adm-form-control" value="<?= Security::e($s['sender_phone']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Sender Email</label>
          <input type="email" name="sender_email" class="adm-form-control" value="<?= Security::e($s['sender_email']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Sender City</label>
          <input type="text" name="sender_city" class="adm-form-control" value="<?= Security::e($s['sender_city']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Sender Country</label>
          <select name="sender_country_id" class="adm-form-control">
            <option value="">— Select —</option>
            <?php foreach ($countries as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($s['sender_country_id']??'')==$c['id']?'selected':'' ?>><?= Security::e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Sender Address</label>
          <input type="text" name="sender_address" class="adm-form-control" value="<?= Security::e($s['sender_address']??'') ?>">
        </div>
      </div>
    </div>

    <!-- Route -->
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-route text-warning"></i> Route</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="adm-form-label">Origin City</label>
          <input type="text" name="origin_city" class="adm-form-control" value="<?= Security::e($s['origin_city']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Origin Country</label>
          <select name="origin_country_id" class="adm-form-control">
            <option value="">— Select —</option>
            <?php foreach ($countries as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($s['origin_country_id']??'')==$c['id']?'selected':'' ?>><?= Security::e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Destination City</label>
          <input type="text" name="destination_city" class="adm-form-control" value="<?= Security::e($s['destination_city']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Destination Country</label>
          <select name="destination_country_id" class="adm-form-control">
            <option value="">— Select —</option>
            <?php foreach ($countries as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($s['destination_country_id']??'')==$c['id']?'selected':'' ?>><?= Security::e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Current Location</label>
          <input type="text" name="current_location" class="adm-form-control" value="<?= Security::e($s['current_location']??'') ?>">
        </div>
      </div>
    </div>

    <!-- Package -->
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-box text-warning"></i> Package Details</div>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="adm-form-label">Weight</label>
          <input type="number" step="0.001" name="weight" class="adm-form-control" value="<?= Security::e($s['weight']??'') ?>">
        </div>
        <div class="col-md-2">
          <label class="adm-form-label">Unit</label>
          <select name="weight_unit" class="adm-form-control">
            <?php foreach (['kg','lb','g'] as $u): ?><option value="<?= $u ?>" <?= ($s['weight_unit']??'kg')===$u?'selected':'' ?>><?= $u ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Package Type</label>
          <select name="package_type_id" class="adm-form-control">
            <option value="">— Select —</option>
            <?php foreach ($pkgtypes as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($s['package_type_id']??'')==$p['id']?'selected':'' ?>><?= Security::e($p['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="adm-form-label">Length</label>
          <input type="number" step="0.01" name="length" class="adm-form-control" value="<?= Security::e($s['length']??'') ?>">
        </div>
        <div class="col-md-3">
          <label class="adm-form-label">Width</label>
          <input type="number" step="0.01" name="width" class="adm-form-control" value="<?= Security::e($s['width']??'') ?>">
        </div>
        <div class="col-md-3">
          <label class="adm-form-label">Height</label>
          <input type="number" step="0.01" name="height" class="adm-form-control" value="<?= Security::e($s['height']??'') ?>">
        </div>
        <div class="col-md-3">
          <label class="adm-form-label">Dim Unit</label>
          <select name="dimension_unit" class="adm-form-control">
            <option value="cm" <?= ($s['dimension_unit']??'cm')==='cm'?'selected':'' ?>>cm</option>
            <option value="in" <?= ($s['dimension_unit']??'cm')==='in'?'selected':'' ?>>in</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Declared Value</label>
          <input type="number" step="0.01" name="declared_value" class="adm-form-control" value="<?= Security::e($s['declared_value']??'') ?>">
        </div>
        <div class="col-md-6">
          <label class="adm-form-label">Currency</label>
          <select name="currency" class="adm-form-control">
            <?php foreach (['USD','EUR','GBP','MAD','DZD','TND','CAD','AUD'] as $cur): ?>
            <option value="<?= $cur ?>" <?= ($s['currency']??'USD')===$cur?'selected':'' ?>><?= $cur ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="adm-form-label">Description</label>
          <textarea name="description" class="adm-form-control" rows="2"><?= Security::e($s['description']??'') ?></textarea>
        </div>
        <div class="col-12">
          <label class="adm-form-label">Special Instructions</label>
          <textarea name="special_instructions" class="adm-form-control" rows="2"><?= Security::e($s['special_instructions']??'') ?></textarea>
        </div>
        <div class="col-12">
          <label class="adm-form-label">Internal Notes (admin only)</label>
          <textarea name="internal_notes" class="adm-form-control" rows="2"><?= Security::e($s['internal_notes']??'') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Tracking History (edit only) -->
    <?php if ($isEdit && !empty($history)): ?>
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-timeline text-warning"></i> Tracking History</div>
      <ul class="adm-timeline">
        <?php foreach (array_reverse($history) as $h): ?>
        <?php $hi = ShipmentModel::STATUSES[$h['status']] ?? ['label'=>$h['status'],'color'=>'secondary','icon'=>'fa-circle']; ?>
        <li class="adm-timeline-item">
          <div class="adm-timeline-dot adm-badge-<?= $hi['color'] ?>" style="border-color:currentColor">
            <i class="fas <?= $hi['icon'] ?>"></i>
          </div>
          <div class="adm-timeline-body">
            <div class="tl-title"><?= Security::e($hi['label']) ?></div>
            <div class="tl-meta">
              <span><i class="far fa-clock me-1"></i><?= date('M d, Y H:i', strtotime($h['occurred_at'])) ?></span>
              <?php if ($h['location']): ?><span><i class="fas fa-map-marker-alt me-1"></i><?= Security::e($h['location']) ?></span><?php endif; ?>
              <?php if ($h['operator_name']): ?><span><i class="fas fa-user me-1"></i><?= Security::e($h['operator_name']) ?></span><?php endif; ?>
            </div>
            <?php if ($h['description']): ?><div class="tl-desc"><?= Security::e($h['description']) ?></div><?php endif; ?>
            <?php if ($h['operator_notes']): ?><div class="tl-notes"><?= Security::e($h['operator_notes']) ?></div><?php endif; ?>
            <div class="mt-2 d-flex gap-1">
              <form method="POST" action="<?= BASE_URL ?>/admin/tracking/<?= $h['id'] ?>/delete">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" data-confirm="Delete this entry?"><i class="fas fa-trash" style="font-size:.7rem"></i></button>
              </form>
            </div>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <!-- Add Tracking Update (edit only) -->
    <?php if ($isEdit): ?>
    <div class="adm-form-section" style="border:2px dashed var(--adm-accent)">
      <div class="adm-form-section-title"><i class="fas fa-plus-circle text-warning"></i> Add Tracking Update</div>
      <form action="<?= BASE_URL ?>/admin/tracking/add" method="POST" id="adm-add-status-form">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <input type="hidden" name="shipment_id" value="<?= $s['id'] ?>">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="adm-form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="adm-form-control" required>
              <option value="">— Select Status —</option>
              <?php foreach ($statuses as $key => $info): ?>
              <option value="<?= $key ?>"><?= $info['label'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Location</label>
            <input type="text" name="location" class="adm-form-control" placeholder="e.g. Paris CDG Airport">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Date & Time</label>
            <input type="datetime-local" name="occurred_at" class="adm-form-control">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Description</label>
            <input type="text" name="description" class="adm-form-control" placeholder="Optional description">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Operator Notes</label>
            <textarea name="operator_notes" class="adm-form-control" rows="2" placeholder="Internal notes..."></textarea>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-warning text-white fw-bold">
              <i class="fas fa-plus me-1"></i> Add Status Update
            </button>
          </div>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>

  <!-- Right Sidebar -->
  <div class="col-lg-4">
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-gear text-warning"></i> Shipment Settings</div>
      <div class="mb-3">
        <label class="adm-form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="adm-form-control" required>
          <?php foreach ($statuses as $key => $info): ?>
          <option value="<?= $key ?>" <?= ($s['status']??'order_received')===$key?'selected':'' ?>><?= $info['label'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Reference Number</label>
        <input type="text" name="reference_number" class="adm-form-control" value="<?= Security::e($s['reference_number']??'') ?>">
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Order Number</label>
        <input type="text" name="order_number" class="adm-form-control" value="<?= Security::e($s['order_number']??'') ?>">
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Website / Client</label>
        <select name="website_id" class="adm-form-control">
          <option value="">— None —</option>
          <?php foreach ($websites as $w): ?>
          <option value="<?= $w['id'] ?>" <?= ($s['website_id']??'')==$w['id']?'selected':'' ?>><?= Security::e($w['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Carrier</label>
        <select name="carrier_id" class="adm-form-control">
          <option value="">— Select —</option>
          <?php foreach ($carriers as $c): ?>
          <option value="<?= $c['id'] ?>" <?= ($s['carrier_id']??'')==$c['id']?'selected':'' ?>><?= Security::e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Shipping Method</label>
        <select name="shipping_method_id" class="adm-form-control">
          <option value="">— Select —</option>
          <?php foreach ($methods as $m): ?>
          <option value="<?= $m['id'] ?>" <?= ($s['shipping_method_id']??'')==$m['id']?'selected':'' ?>><?= Security::e($m['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Service Level</label>
        <input type="text" name="service_level" class="adm-form-control" value="<?= Security::e($s['service_level']??'') ?>">
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Shipping Date</label>
        <input type="date" name="shipping_date" class="adm-form-control" value="<?= Security::e($s['shipping_date']??'') ?>">
      </div>
      <div class="mb-3">
        <label class="adm-form-label">Estimated Delivery</label>
        <input type="date" name="estimated_delivery" class="adm-form-control" value="<?= Security::e($s['estimated_delivery']??'') ?>">
      </div>
    </div>

    <?php if ($isEdit): ?>
    <div class="adm-form-section">
      <div class="adm-form-section-title"><i class="fas fa-barcode text-warning"></i> Tracking Number</div>
      <div class="d-flex align-items-center gap-2">
        <code class="flex-grow-1 p-2 bg-light rounded" style="font-size:.9rem"><?= Security::e($s['tracking_number']) ?></code>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="<?= Security::e($s['tracking_number']) ?>" title="Copy">
          <i class="fas fa-copy"></i>
        </button>
      </div>
      <div class="mt-2">
        <a href="<?= BASE_URL ?>/track/<?= Security::e($s['tracking_number']) ?>" target="_blank" class="btn btn-sm btn-outline-primary w-100">
          <i class="fas fa-external-link-alt me-1"></i> Public Tracking Page
        </a>
      </div>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-warning text-white fw-bold flex-grow-1">
        <i class="fas fa-save me-1"></i> Save Changes
      </button>
      <form method="POST" action="<?= BASE_URL ?>/admin/shipments/<?= $s['id'] ?>/duplicate">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-outline-secondary" title="Duplicate" data-confirm="Duplicate this shipment?">
          <i class="fas fa-copy"></i>
        </button>
      </form>
    </div>
    <?php else: ?>
    <button type="submit" class="btn btn-warning text-white fw-bold w-100" style="padding:.9rem">
      <i class="fas fa-plus me-1"></i> Create Shipment
    </button>
    <?php endif; ?>
  </div>
</form>
