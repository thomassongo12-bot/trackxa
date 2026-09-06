<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-user me-2 text-warning"></i>My Profile</h1></div>
</div>
<div class="row g-3 justify-content-center">
  <div class="col-lg-6">
    <div class="adm-form-section">
      <form action="<?= BASE_URL ?>/admin/profile" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
        <div class="text-center mb-4">
          <img src="<?= !empty($admin['avatar']) ? ASSETS_URL.'/'.$admin['avatar'] : 'https://ui-avatars.com/api/?name='.urlencode($admin['name']).'&background=1a3c6e&color=fff&size=120' ?>"
               id="avatar-preview" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid var(--adm-border)">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Avatar</label>
          <input type="file" name="avatar" class="adm-form-control" accept="image/*" data-preview="avatar-preview">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Full Name</label>
          <input type="text" name="name" class="adm-form-control" value="<?= Security::e($admin['name']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Email</label>
          <input type="email" class="adm-form-control" value="<?= Security::e($admin['email']) ?>" disabled>
          <div style="font-size:.75rem;color:#888;margin-top:.3rem">Email cannot be changed here.</div>
        </div>
        <hr>
        <div style="font-size:.85rem;font-weight:600;color:var(--adm-primary);margin-bottom:.8rem"><i class="fas fa-lock me-1 text-warning"></i>Change Password (leave blank to keep current)</div>
        <div class="mb-3">
          <label class="adm-form-label">Current Password</label>
          <input type="password" name="current_password" class="adm-form-control" placeholder="••••••••">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">New Password</label>
          <input type="password" name="new_password" class="adm-form-control" placeholder="Min. 8 characters">
        </div>
        <button type="submit" class="btn btn-warning text-white fw-bold w-100">
          <i class="fas fa-save me-1"></i> Save Changes
        </button>
      </form>
    </div>
  </div>
</div>
