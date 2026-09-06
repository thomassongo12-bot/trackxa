<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title"><i class="fas fa-gear me-2 text-warning"></i>Settings</h1>
    <p class="adm-page-subtitle">Configure your platform</p>
  </div>
</div>

<form action="<?= BASE_URL ?>/admin/settings" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">

  <!-- Tab nav -->
  <ul class="nav nav-tabs mb-3" id="settingsTabs">
    <?php foreach (['general'=>'General','email'=>'Email','seo'=>'SEO','social'=>'Social','security'=>'Security','analytics'=>'Analytics'] as $tab => $label): ?>
    <li class="nav-item">
      <button class="nav-link <?= $tab==='general'?'active':'' ?>" data-bs-toggle="tab" data-bs-target="#tab-<?= $tab ?>"><?= $label ?></button>
    </li>
    <?php endforeach; ?>
  </ul>

  <div class="tab-content">
    <!-- General -->
    <div class="tab-pane fade show active" id="tab-general">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-globe text-warning"></i> General Settings</div>
        <div class="row g-3">
          <?php $genFields = ['site_name'=>'Site Name','site_tagline'=>'Tagline','site_email'=>'Contact Email','site_phone'=>'Phone','site_address'=>'Address','tracking_prefix'=>'Tracking Prefix','whatsapp_number'=>'WhatsApp Number','timezone'=>'Timezone','currency'=>'Currency','default_language'=>'Default Language']; ?>
          <?php foreach ($genFields as $key => $label): ?>
          <div class="col-md-6">
            <label class="adm-form-label"><?= $label ?></label>
            <input type="text" name="<?= $key ?>" class="adm-form-control" value="<?= Security::e($grouped['general'][$key] ?? '') ?>">
          </div>
          <?php endforeach; ?>
          <div class="col-md-6">
            <label class="adm-form-label">Site Logo</label>
            <?php if (!empty($grouped['general']['site_logo'])): ?>
            <div class="mb-2"><img src="<?= ASSETS_URL.'/'.$grouped['general']['site_logo'] ?>" style="height:50px"></div>
            <?php endif; ?>
            <input type="file" name="site_logo" class="adm-form-control" accept="image/*" data-preview="logo-preview">
            <img id="logo-preview" src="" style="display:none;height:50px;margin-top:.5rem;border-radius:8px">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Favicon</label>
            <input type="file" name="site_favicon" class="adm-form-control" accept="image/*">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Maintenance Mode</label>
            <select name="maintenance_mode" class="adm-form-control">
              <option value="0" <?= ($grouped['general']['maintenance_mode']??'0')==='0'?'selected':'' ?>>Off</option>
              <option value="1" <?= ($grouped['general']['maintenance_mode']??'0')==='1'?'selected':'' ?>>On</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Email -->
    <div class="tab-pane fade" id="tab-email">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-envelope text-warning"></i> SMTP Settings</div>
        <div class="row g-3">
          <?php $emailFields = ['smtp_host'=>'SMTP Host','smtp_port'=>'SMTP Port','smtp_user'=>'SMTP Username','smtp_secure'=>'Encryption (tls/ssl)']; ?>
          <?php foreach ($emailFields as $k => $l): ?>
          <div class="col-md-6">
            <label class="adm-form-label"><?= $l ?></label>
            <input type="text" name="<?= $k ?>" class="adm-form-control" value="<?= Security::e($grouped['email'][$k] ?? '') ?>">
          </div>
          <?php endforeach; ?>
          <div class="col-md-6">
            <label class="adm-form-label">SMTP Password</label>
            <input type="password" name="smtp_pass" class="adm-form-control" placeholder="••••••••">
          </div>
        </div>
      </div>
    </div>

    <!-- SEO -->
    <div class="tab-pane fade" id="tab-seo">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-magnifying-glass-chart text-warning"></i> SEO Settings</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">Meta Title</label>
            <input type="text" name="meta_title" class="adm-form-control" value="<?= Security::e($grouped['seo']['meta_title'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Meta Description</label>
            <textarea name="meta_description" class="adm-form-control" rows="3"><?= Security::e($grouped['seo']['meta_description'] ?? '') ?></textarea>
          </div>
          <div class="col-12">
            <label class="adm-form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="adm-form-control" value="<?= Security::e($grouped['seo']['meta_keywords'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Google Maps API Key</label>
            <input type="text" name="google_maps_key" class="adm-form-control" value="<?= Security::e($grouped['general']['google_maps_key'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

    <!-- Social -->
    <div class="tab-pane fade" id="tab-social">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-share-nodes text-warning"></i> Social Media</div>
        <div class="row g-3">
          <?php foreach (['facebook_url'=>'Facebook URL','twitter_url'=>'Twitter/X URL','instagram_url'=>'Instagram URL','linkedin_url'=>'LinkedIn URL'] as $k => $l): ?>
          <div class="col-md-6">
            <label class="adm-form-label"><?= $l ?></label>
            <input type="url" name="<?= $k ?>" class="adm-form-control" value="<?= Security::e($grouped['social'][$k] ?? '') ?>">
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Security -->
    <div class="tab-pane fade" id="tab-security">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-shield text-warning"></i> Security</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="adm-form-label">reCAPTCHA Site Key</label>
            <input type="text" name="recaptcha_site_key" class="adm-form-control" value="<?= Security::e($grouped['security']['recaptcha_site_key'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">reCAPTCHA Secret Key</label>
            <input type="text" name="recaptcha_secret_key" class="adm-form-control" value="<?= Security::e($grouped['security']['recaptcha_secret_key'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

    <!-- Analytics -->
    <div class="tab-pane fade" id="tab-analytics">
      <div class="adm-form-section">
        <div class="adm-form-section-title"><i class="fas fa-chart-bar text-warning"></i> Analytics</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="adm-form-label">Google Analytics ID</label>
            <input type="text" name="google_analytics" class="adm-form-control" placeholder="G-XXXXXXXXXX" value="<?= Security::e($grouped['analytics']['google_analytics'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <button type="submit" class="btn btn-warning text-white fw-bold px-4">
      <i class="fas fa-save me-1"></i> Save All Settings
    </button>
  </div>
</form>
