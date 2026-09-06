<div class="container py-4">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="brand">Track<span>Xa</span></div>
      <p>Admin Control Panel</p>
    </div>
    <h2>Welcome Back</h2>
    <p style="color:#6c757d;font-size:.88rem;margin-bottom:1.5rem">Sign in to your admin account</p>
    <?php if ($error): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="font-size:.85rem">
      <i class="fas fa-exclamation-circle"></i><?= Security::e($error) ?>
    </div>
    <?php endif; ?>
    <?php if ($success = Session::getInstance()->getFlash('login_success')): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="font-size:.85rem">
      <i class="fas fa-check-circle"></i><?= Security::e($success) ?>
    </div>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>/admin/login" method="POST" novalidate>
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="admin@trackxa.com" required autofocus autocomplete="username">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" class="form-control pass-input" placeholder="••••••••" required autocomplete="current-password">
          <span class="input-group-text pass-toggle"><i class="fas fa-eye"></i></span>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember">
          <label class="form-check-label" for="remember" style="font-size:.85rem">Remember me</label>
        </div>
        <a href="<?= BASE_URL ?>/admin/forgot-password" style="font-size:.85rem;color:#1a3c6e;font-weight:600">Forgot password?</a>
      </div>
      <button type="submit" class="btn-auth">
        <i class="fas fa-right-to-bracket me-2"></i>Sign In
      </button>
    </form>
    <div class="auth-footer">
      <a href="<?= BASE_URL ?>"><i class="fas fa-arrow-left me-1"></i>Back to Website</a>
    </div>
  </div>
</div>
