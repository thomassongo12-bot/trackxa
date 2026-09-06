<?php
class AdminLangController extends Controller {
    public function switch(array $params = []): never {
        $this->requireAdmin();
        $code = $params['code'] ?? 'en';
        AdminLang::set($code);
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/admin/dashboard';
        $this->redirect($referer);
    }
}
