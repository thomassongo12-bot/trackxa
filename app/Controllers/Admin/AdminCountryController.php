<?php
class AdminCountryController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        $countries = Database::getInstance()->query("SELECT * FROM countries ORDER BY name")->fetchAll();
        $this->view('admin.countries', ['title' => 'Countries – Admin', 'countries' => $countries], 'admin');
    }
}
