<!-- Track Page -->
<section class="tx-page-hero">
  <div class="container">
    <div class="text-center text-white mb-4">
      <h1><i class="fas fa-magnifying-glass-location me-2" style="color:var(--tx-accent)"></i><?= $lang->get('track_your_shipment') ?></h1>
      <p><?= $lang->get('enter_tracking') ?></p>
    </div>
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-7">
        <?php if (!empty($error)): ?>
        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3">
          <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
          <span><?= $error ?></span>
        </div>
        <?php endif; ?>
        <div class="tx-track-box">
          <form action="<?= BASE_URL ?>/track" method="POST" id="tx-track-form">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
            <div class="row g-2">
              <div class="col-12 col-sm-8 col-md-8">
                <input type="text" name="tracking_number" id="tracking_number" class="tx-track-input w-100"
                       placeholder="e.g. TXA1A2B3C4D5E6 or REF-123456"
                       value="<?= Security::e($number ?? '') ?>" autocomplete="off" autofocus>
              </div>
              <div class="col-12 col-sm-4 col-md-4">
                <button type="submit" class="tx-track-btn w-100" style="justify-content:center;white-space:nowrap;">
                  <i class="fas fa-search me-1"></i> <?= $lang->get('track_btn') ?>
                </button>
              </div>
            </div>
            <p class="mt-2 mb-0" style="color:rgba(255,255,255,.5);font-size:.75rem">
              <i class="fas fa-info-circle me-1"></i> You can search by tracking number, reference number, or order number.
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features row -->
<section class="tx-section-dark py-5">
  <div class="container">
    <div class="row g-3 text-center">
      <?php foreach ([
        ['icon'=>'fa-bolt','title'=>'Instant Results','desc'=>'Real-time data'],
        ['icon'=>'fa-globe','title'=>'116+ Countries','desc'=>'Global coverage'],
        ['icon'=>'fa-lock','title'=>'Secure','desc'=>'Private & encrypted'],
        ['icon'=>'fa-bell','title'=>'Email Alerts','desc'=>'Auto notifications'],
      ] as $f): ?>
      <div class="col-6 col-md-3">
        <div class="p-3">
          <i class="fas <?= $f['icon'] ?>" style="font-size:1.5rem;color:var(--tx-primary);margin-bottom:.5rem"></i>
          <div style="font-weight:700;font-size:.9rem;color:var(--tx-primary)"><?= $f['title'] ?></div>
          <div style="font-size:.78rem;color:#64748b"><?= $f['desc'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── TRACKING STATUSES ─────────────────────────────────── -->
<section class="tx-section" id="tracking-statuses">
  <div class="container">
    <div class="text-center mb-5">
      <span class="tx-blog-category"><?= $lang->get('statuses_badge') ?></span>
      <h2 class="tx-section-title mt-2"><?= $lang->get('statuses_title') ?></h2>
      <div class="tx-divider"></div>
      <p class="tx-section-subtitle mx-auto"><?= $lang->get('statuses_subtitle') ?></p>
    </div>

    <?php
    $statusGroups = [
      [
        'group'  => $lang->get('status_group_processing'),
        'color'  => '#64748b',
        'items'  => [
          'order_received'   => $lang->get('status_desc_order_received'),
          'shipment_created' => $lang->get('status_desc_shipment_created'),
          'preparing'        => $lang->get('status_desc_preparing'),
        ]
      ],
      [
        'group'  => $lang->get('status_group_transit'),
        'color'  => '#1a3c6e',
        'items'  => [
          'picked_up'        => $lang->get('status_desc_picked_up'),
          'at_warehouse'     => $lang->get('status_desc_at_warehouse'),
          'in_transit'       => $lang->get('status_desc_in_transit'),
          'arrived_airport'  => $lang->get('status_desc_arrived_airport'),
          'departed_airport' => $lang->get('status_desc_departed_airport'),
        ]
      ],
      [
        'group'  => $lang->get('status_group_customs'),
        'color'  => '#e8a020',
        'items'  => [
          'customs_clearance' => $lang->get('status_desc_customs_clearance'),
          'released_customs'  => $lang->get('status_desc_released_customs'),
        ]
      ],
      [
        'group'  => $lang->get('status_group_delivery'),
        'color'  => '#28a745',
        'items'  => [
          'out_for_delivery' => $lang->get('status_desc_out_for_delivery'),
          'delivered'        => $lang->get('status_desc_delivered'),
        ]
      ],
      [
        'group'  => $lang->get('status_group_issues'),
        'color'  => '#dc3545',
        'items'  => [
          'delivery_failed' => $lang->get('status_desc_delivery_failed'),
          'returned'        => $lang->get('status_desc_returned'),
          'delayed'         => $lang->get('status_desc_delayed'),
          'on_hold'         => $lang->get('status_desc_on_hold'),
          'cancelled'       => $lang->get('status_desc_cancelled'),
        ]
      ],
    ];

    $badgeColors = [
      'secondary' => ['bg'=>'#e2e3e5','text'=>'#383d41'],
      'info'      => ['bg'=>'#d1ecf1','text'=>'#0c5460'],
      'primary'   => ['bg'=>'#cfe2ff','text'=>'#084298'],
      'warning'   => ['bg'=>'#fff3cd','text'=>'#856404'],
      'success'   => ['bg'=>'#d4edda','text'=>'#155724'],
      'danger'    => ['bg'=>'#f8d7da','text'=>'#721c24'],
    ];
    $statuses = ShipmentModel::STATUSES;
    ?>

    <div class="row g-4">
      <?php foreach ($statusGroups as $group): ?>
      <div class="col-12">
        <div class="tx-card" style="border-left:4px solid <?= $group['color'] ?>;overflow:visible">
          <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:.75rem">
            <div style="width:10px;height:10px;border-radius:50%;background:<?= $group['color'] ?>;flex-shrink:0"></div>
            <h5 style="margin:0;font-weight:800;color:var(--tx-primary);font-size:1rem"><?= $group['group'] ?></h5>
          </div>
          <div style="padding:1rem 1.5rem">
            <div class="row g-3">
              <?php foreach ($group['items'] as $key => $desc):
                $s  = $statuses[$key] ?? ['label'=>$key,'color'=>'secondary','icon'=>'fa-circle'];
                $bc = $badgeColors[$s['color']] ?? $badgeColors['secondary'];
              ?>
              <div class="col-12 col-md-6 col-lg-4">
                <div style="display:flex;gap:.9rem;align-items:flex-start;padding:.75rem;background:#f8fafc;border-radius:10px;border:1px solid #e8edf3;height:100%">
                  <div style="width:38px;height:38px;border-radius:10px;background:<?= $bc['bg'] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas <?= $s['icon'] ?>" style="color:<?= $bc['text'] ?>;font-size:.9rem"></i>
                  </div>
                  <div>
                    <div style="display:inline-flex;align-items:center;gap:.4rem;background:<?= $bc['bg'] ?>;color:<?= $bc['text'] ?>;padding:.18rem .6rem;border-radius:100px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                      <?= Security::e($s['label']) ?>
                    </div>
                    <p style="margin:0;font-size:.82rem;color:#64748b;line-height:1.55"><?= $desc ?></p>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
