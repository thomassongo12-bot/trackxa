<div class="container py-4">
  <div class="auth-card">
    <div class="auth-logo"><div class="brand">Track<span>Xa</span></div></div>
    <h2>Reset Password</h2>
    <p style="color:#6c757d;font-size:.88rem;margin-bottom:1.5rem">Choose a strong new password.</p>
    <?php if ($error ?? null): ?><div class="alert alert-danger py-2 mb-3" style="font-size:.85rem"><?= Security::e($error) ?></div><?php endif; ?>
    <form action="<?= BASE_URL ?>/admin/reset-password" method="POST">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <input type="hidden" name="token" value="<?= Security::e($token ?? '') ?>">
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <div class="input-group">
          <input type="password" name="password" class="form-control pass-input" placeholder="Min. 8 characters" required minlength="8">
          <span class="input-group-text pass-toggle"><i class="fas fa-eye"></i></span>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" class="form-control pass-input" placeholder="Repeat password" required>
          <span class="input-group-text pass-toggle"><i class="fas fa-eye"></i></span>
        </div>
      </div>
      <button type="submit" class="btn-auth"><i class="fas fa-lock me-2"></i>Reset Password</button>
    </form>
  </div>
</div>
