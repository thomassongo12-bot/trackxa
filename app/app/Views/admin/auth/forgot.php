<div class="container py-4">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="brand">Track<span>Xa</span></div>
    </div>
    <h2>Forgot Password</h2>
    <p style="color:#6c757d;font-size:.88rem;margin-bottom:1.5rem">Enter your email and we'll send you a reset link.</p>
    <?php if ($success ?? null): ?><div class="alert alert-success py-2 mb-3" style="font-size:.85rem"><i class="fas fa-check-circle me-1"></i><?= Security::e($success) ?></div><?php endif; ?>
    <?php if ($error ?? null): ?><div class="alert alert-danger py-2 mb-3" style="font-size:.85rem"><?= Security::e($error) ?></div><?php endif; ?>
    <form action="<?= BASE_URL ?>/admin/forgot-password" method="POST">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf ?>">
      <div class="mb-4">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" required autofocus>
      </div>
      <button type="submit" class="btn-auth"><i class="fas fa-paper-plane me-2"></i>Send Reset Link</button>
    </form>
    <div class="auth-footer mt-3"><a href="<?= BASE_URL ?>/admin/login"><i class="fas fa-arrow-left me-1"></i>Back to Login</a></div>
  </div>
</div>
