<?php
class LangController extends Controller {
    public function switch(array $params = []): void {
        $code = $params['code'] ?? DEFAULT_LANG;
        Lang::set($code);
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/';
        $this->redirect($referer);
    }
}
