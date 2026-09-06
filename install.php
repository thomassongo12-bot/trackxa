<?php
/**
 * TrackXa – Quick Installer
 * Run once at: http://yoursite.com/install.php
 * DELETE this file after installation!
 */

// Security: block if already installed
$lockFile = __DIR__ . '/storage/.installed';
if (file_exists($lockFile)) {
    die('<h2 style="font-family:sans-serif;color:#c53030;text-align:center;padding:3rem">Already installed. Delete install.php for security.</h2>');
}

$errors  = [];
$success = false;
$step    = (int)($_POST['step'] ?? 0);

// Step 2: Process installation
if ($step === 2 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $host   = trim($_POST['db_host']  ?? 'localhost');
    $name   = trim($_POST['db_name']  ?? '');
    $user   = trim($_POST['db_user']  ?? '');
    $pass   = trim($_POST['db_pass']  ?? '');
    $sName  = trim($_POST['site_name']?? 'TrackXa');
    $sEmail = trim($_POST['site_email']?? '');
    $aName  = trim($_POST['admin_name']?? 'Admin');
    $aEmail = trim($_POST['admin_email']?? '');
    $aPass  = trim($_POST['admin_pass']?? '');

    if (!$name || !$user || !$aEmail || !$aPass) {
        $errors[] = 'Please fill in all required fields.';
    } elseif (strlen($aPass) < 8) {
        $errors[] = 'Admin password must be at least 8 characters.';
    } else {
        try {
            $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            // Create DB
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$name}`");

            // Import SQL
            $sql = file_get_contents(__DIR__ . '/database/trackxa.sql');
            // Split and execute
            foreach (array_filter(array_map('trim', explode(';', $sql))) as $query) {
                if ($query) $pdo->exec($query);
            }

            // Update admin
            $hash = password_hash($aPass, PASSWORD_BCRYPT, ['cost'=>12]);
            $pdo->prepare("UPDATE admins SET name=?,email=?,password=? WHERE id=1")->execute([$aName,$aEmail,$hash]);
            // Update settings
            $pdo->prepare("UPDATE settings SET value=? WHERE `key`='site_name'")->execute([$sName]);
            $pdo->prepare("UPDATE settings SET value=? WHERE `key`='site_email'")->execute([$sEmail]);

            // Write config
            $configContent = "<?php\ndefine('ENV', 'production');\ndefine('ROOT_PATH', dirname(__DIR__));\ndefine('APP_PATH', ROOT_PATH.'/app');\ndefine('CORE_PATH', ROOT_PATH.'/core');\ndefine('PUBLIC_PATH', ROOT_PATH.'/public');\ndefine('STORAGE_PATH', ROOT_PATH.'/storage');\ndefine('LANG_PATH', ROOT_PATH.'/lang');\n\n\$protocol = (!empty(\$_SERVER['HTTPS'])&&\$_SERVER['HTTPS']!=='off')?'https':'http';\n\$host = \$_SERVER['HTTP_HOST']??'localhost';\n\$script = dirname(\$_SERVER['SCRIPT_NAME']??'');\n\$base = rtrim(\$protocol.'://'.\$host.\$script, '/');\ndefine('BASE_URL', \$base);\ndefine('ASSETS_URL', BASE_URL.'/public');\n\ndefine('DB_HOST',    '{$host}');\ndefine('DB_NAME',    '{$name}');\ndefine('DB_USER',    '{$user}');\ndefine('DB_PASS',    '{$pass}');\ndefine('DB_CHARSET', 'utf8mb4');\n\ndefine('SECRET_KEY',     '".bin2hex(random_bytes(32))."');\ndefine('CSRF_TOKEN_NAME','_csrf_token');\ndefine('SESSION_NAME',   'trackxa_sess');\n\ndefine('DEFAULT_LANG',    'en');\ndefine('SUPPORTED_LANGS', ['en','fr','es','de','it','pt','ar']);\ndefine('TIMEZONE',        'UTC');\ndefine('UPLOAD_MAX_SIZE', 5*1024*1024);\ndefine('ALLOWED_IMG_TYPES', ['image/jpeg','image/png','image/webp','image/gif']);\n\ndefine('API_VERSION',    'v1');\ndefine('API_RATE_LIMIT', 1000);\n\ndate_default_timezone_set(TIMEZONE);\nini_set('display_errors', 0);\nerror_reporting(0);\n";
            file_put_contents(__DIR__ . '/config/config.php', $configContent);

            // Create lock file
            if (!is_dir(__DIR__.'/storage')) mkdir(__DIR__.'/storage', 0755, true);
            file_put_contents($lockFile, date('Y-m-d H:i:s'));

            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>TrackXa Installer</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#0f2647,#1a3c6e);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
.install-card{background:#fff;border-radius:20px;padding:3rem;width:100%;max-width:560px;box-shadow:0 30px 80px rgba(0,0,0,.3);}
.brand{text-align:center;margin-bottom:2rem;}
.brand h1{font-size:2rem;font-weight:800;color:#1a3c6e;}
.brand h1 span{color:#e8a020;}
.form-label{font-weight:600;font-size:.85rem;}
.btn-install{background:#e8a020;color:#fff;border:none;border-radius:10px;padding:.8rem;font-weight:700;font-size:1rem;width:100%;}
.btn-install:hover{background:#c8860a;color:#fff;}
</style>
</head>
<body>
<div class="install-card">
  <div class="brand">
    <h1>Track<span>Xa</span></h1>
    <p class="text-muted">Installation Wizard</p>
  </div>

  <?php if ($success): ?>
  <div class="text-center">
    <div style="font-size:4rem;color:#28a745">✓</div>
    <h3 style="color:#1a3c6e;font-weight:800">Installation Complete!</h3>
    <p class="text-muted">TrackXa has been installed successfully.</p>
    <div class="alert alert-warning text-start mt-3">
      <strong>⚠ Security:</strong> Delete <code>install.php</code> from your server immediately!
    </div>
    <a href="<?= dirname($_SERVER['PHP_SELF']) ?>/public/" class="btn btn-install mt-2">Go to Website</a>
    <a href="<?= dirname($_SERVER['PHP_SELF']) ?>/public/admin/login" class="btn btn-outline-primary mt-2 w-100">Admin Login</a>
  </div>

  <?php else: ?>
  <?php foreach ($errors as $err): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endforeach; ?>

  <form method="POST">
    <input type="hidden" name="step" value="2">
    <h5 style="font-weight:700;color:#1a3c6e;margin-bottom:1rem">🗄 Database</h5>
    <div class="row g-2 mb-3">
      <div class="col-md-6">
        <label class="form-label">Host</label>
        <input type="text" name="db_host" class="form-control" value="localhost" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Database Name *</label>
        <input type="text" name="db_name" class="form-control" placeholder="trackxa" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Username *</label>
        <input type="text" name="db_user" class="form-control" placeholder="root" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="db_pass" class="form-control">
      </div>
    </div>

    <h5 style="font-weight:700;color:#1a3c6e;margin-bottom:1rem">⚙ Site Settings</h5>
    <div class="row g-2 mb-3">
      <div class="col-md-6">
        <label class="form-label">Site Name</label>
        <input type="text" name="site_name" class="form-control" value="TrackXa">
      </div>
      <div class="col-md-6">
        <label class="form-label">Site Email</label>
        <input type="email" name="site_email" class="form-control">
      </div>
    </div>

    <h5 style="font-weight:700;color:#1a3c6e;margin-bottom:1rem">👤 Admin Account</h5>
    <div class="row g-2 mb-4">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="admin_name" class="form-control" value="Super Admin">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email *</label>
        <input type="email" name="admin_email" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label">Password * (min 8 chars)</label>
        <input type="password" name="admin_pass" class="form-control" required minlength="8">
      </div>
    </div>

    <button type="submit" class="btn-install">
      🚀 Install TrackXa
    </button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
