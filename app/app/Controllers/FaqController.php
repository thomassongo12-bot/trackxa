<?php
class FaqController extends Controller {
    public function index(array $params = []): void {
        $locale = Lang::getInstance()->getLocale();
        $stmt   = Database::getInstance()->prepare("SELECT * FROM faqs WHERE status=1 AND lang=? ORDER BY sort_order");
        $stmt->execute([$locale]);
        $faqs = $stmt->fetchAll();
        $this->view('public.faq', [
            'title' => 'FAQ – ' . Settings::get('site_name', 'TrackXa'),
            'faqs'  => $faqs,
        ]);
    }
}
