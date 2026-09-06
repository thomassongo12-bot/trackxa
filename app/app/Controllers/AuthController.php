<?php
class AuthController extends Controller {
    private AdminModel $admin;

    public function __construct() { $this->admin = new AdminModel(); }

    public function loginForm(array $params = []): void {
        if (Session::getInstance()->isAdmin()) $this->redirect(BASE_URL . '/admin/dashboard');
        $this->view('admin.auth.login', [
            'title' => 'Admin Login – ' . Settings::get('site_name', 'TrackXa'),
            'error' => Session::getInstance()->getFlash('login_error'),
        ], 'auth');
    }

    public function login(array $params = []): void {
        $this->validateCsrf();
        if (!Security::rateLimit('login_' . Security::ip(), 5, 300)) {
            Session::getInstance()->flash('login_error', 'Too many login attempts. Try again in 5 minutes.');
            $this->redirect(BASE_URL . '/admin/login');
        }

        $email    = $this->post('email', '');
        $password = $this->post('password', '');

        if (empty($email) || empty($password)) {
            Session::getInstance()->flash('login_error', 'Please enter your email and password.');
            $this->redirect(BASE_URL . '/admin/login');
        }

        $admin = $this->admin->findByEmail($email);
        if (!$admin || !Security::verifyPassword($password, $admin['password'])) {
            Session::getInstance()->flash('login_error', 'Invalid email or password.');
            $this->redirect(BASE_URL . '/admin/login');
        }

        if ($admin['status'] !== 'active') {
            Session::getInstance()->flash('login_error', 'Your account has been disabled.');
            $this->redirect(BASE_URL . '/admin/login');
        }

        // Update last login
        $this->admin->update($admin['id'], [
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip'    => Security::ip(),
        ]);

        Session::getInstance()->regenerate();
        Session::getInstance()->set('admin_id',   $admin['id']);
        Session::getInstance()->set('admin_name', $admin['name']);
        Session::getInstance()->set('admin_email',$admin['email']);
        Session::getInstance()->set('admin_role', $admin['role']);

        $this->admin->logActivity($admin['id'], 'LOGIN', 'admins', $admin['id'], 'Successful login');
        $this->redirect(BASE_URL . '/admin/dashboard');
    }

    public function logout(array $params = []): void {
        $adminId = Session::getInstance()->get('admin_id');
        if ($adminId) $this->admin->logActivity($adminId, 'LOGOUT');
        Session::getInstance()->destroy();
        $this->redirect(BASE_URL . '/admin/login');
    }

    public function forgotForm(array $params = []): void {
        $this->view('admin.auth.forgot', [
            'title'   => 'Forgot Password – ' . Settings::get('site_name', 'TrackXa'),
            'success' => Session::getInstance()->getFlash('forgot_success'),
            'error'   => Session::getInstance()->getFlash('forgot_error'),
        ], 'auth');
    }

    public function forgot(array $params = []): void {
        $this->validateCsrf();
        $email = $this->post('email', '');
        $admin = $this->admin->findByEmail($email);

        // Always show success to prevent email enumeration
        Session::getInstance()->flash('forgot_success', 'If that email exists, a reset link has been sent.');

        if ($admin) {
            $token   = Security::generateToken();
            $expires = date('Y-m-d H:i:s', time() + 3600);
            Database::getInstance()->prepare(
                "INSERT INTO password_resets (email,token,expires_at) VALUES (?,?,?)"
            )->execute([$email, $token, $expires]);

            $link = BASE_URL . '/admin/reset-password/' . $token;
            $body = "<p>Click the link below to reset your password (valid 1 hour):</p><p><a href='{$link}'>{$link}</a></p>";
            EmailService::send($email, 'Password Reset – ' . Settings::get('site_name', 'TrackXa'), $body);
        }
        $this->redirect(BASE_URL . '/admin/forgot-password');
    }

    public function resetForm(array $params = []): void {
        $token = $params['token'] ?? '';
        $stmt  = Database::getInstance()->prepare(
            "SELECT * FROM password_resets WHERE token=? AND used=0 AND expires_at > NOW() LIMIT 1"
        );
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        if (!$reset) {
            Session::getInstance()->flash('login_error', 'Invalid or expired reset link.');
            $this->redirect(BASE_URL . '/admin/login');
        }
        $this->view('admin.auth.reset', [
            'title' => 'Reset Password – ' . Settings::get('site_name', 'TrackXa'),
            'token' => $token,
            'error' => Session::getInstance()->getFlash('reset_error'),
        ], 'auth');
    }

    public function reset(array $params = []): void {
        $this->validateCsrf();
        $token    = $this->post('token', '');
        $password = $this->post('password', '');
        $confirm  = $this->post('confirm_password', '');

        if (strlen($password) < 8 || $password !== $confirm) {
            Session::getInstance()->flash('reset_error', 'Passwords must match and be at least 8 characters.');
            $this->redirect(BASE_URL . '/admin/reset-password/' . $token);
        }

        $stmt = Database::getInstance()->prepare(
            "SELECT * FROM password_resets WHERE token=? AND used=0 AND expires_at > NOW() LIMIT 1"
        );
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        if (!$reset) {
            Session::getInstance()->flash('login_error', 'Invalid or expired reset link.');
            $this->redirect(BASE_URL . '/admin/login');
        }

        $admin = $this->admin->findByEmail($reset['email']);
        if ($admin) {
            $this->admin->update($admin['id'], ['password' => Security::hashPassword($password)]);
            Database::getInstance()->prepare("UPDATE password_resets SET used=1 WHERE token=?")->execute([$token]);
            Session::getInstance()->flash('login_success', 'Password reset successfully. Please log in.');
        }
        $this->redirect(BASE_URL . '/admin/login');
    }
}
