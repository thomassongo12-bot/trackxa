<?php
/**
 * TrackXa - Front Controller
 */
define('TRACKXA', true);

require_once dirname(__DIR__) . '/config/config.php';
require_once CORE_PATH . '/Autoloader.php';

Autoloader::register();
Session::getInstance();
Lang::getInstance();

// ── Routes ────────────────────────────────────────────────────
$router = new Router();

// Public
$router->get('/',                          [HomeController::class, 'index']);
$router->get('/track',                     [TrackController::class, 'index']);
$router->get('/track/{number}',            [TrackController::class, 'show']);
$router->post('/track',                    [TrackController::class, 'search']);
$router->get('/blog',                      [BlogController::class, 'index']);
$router->get('/blog/{slug}',               [BlogController::class, 'show']);
$router->get('/blog/category/{slug}',      [BlogController::class, 'category']);
$router->get('/contact',                   [ContactController::class, 'index']);
$router->post('/contact',                  [ContactController::class, 'send']);
$router->get('/faq',                       [FaqController::class, 'index']);
$router->get('/lang/{code}',               [LangController::class, 'switch']);
$router->get('/sitemap.xml',               [SeoController::class, 'sitemap']);
$router->get('/robots.txt',                [SeoController::class, 'robots']);

// Admin Auth
$router->get('/admin/login',               [AuthController::class, 'loginForm']);
$router->post('/admin/login',              [AuthController::class, 'login']);
$router->get('/admin/logout',              [AuthController::class, 'logout']);
$router->get('/admin/forgot-password',     [AuthController::class, 'forgotForm']);
$router->post('/admin/forgot-password',    [AuthController::class, 'forgot']);
$router->get('/admin/reset-password/{token}', [AuthController::class, 'resetForm']);
$router->post('/admin/reset-password',     [AuthController::class, 'reset']);

$router->get('/admin/lang/{code}',         [AdminLangController::class, 'switch']);

// Admin Dashboard
$router->get('/admin',                     [AdminDashboardController::class, 'index']);
$router->get('/admin/dashboard',           [AdminDashboardController::class, 'index']);

// Admin Shipments
$router->get('/admin/shipments',           [AdminShipmentController::class, 'index']);
$router->get('/admin/shipments/create',    [AdminShipmentController::class, 'create']);
$router->post('/admin/shipments/create',   [AdminShipmentController::class, 'store']);
$router->get('/admin/shipments/{id}/edit', [AdminShipmentController::class, 'edit']);
$router->post('/admin/shipments/{id}/edit',[AdminShipmentController::class, 'update']);
$router->post('/admin/shipments/{id}/delete',[AdminShipmentController::class, 'delete']);
$router->post('/admin/shipments/{id}/archive',[AdminShipmentController::class, 'archive']);
$router->get('/admin/shipments/{id}/view', [AdminShipmentController::class, 'detail']);
$router->post('/admin/shipments/{id}/duplicate',[AdminShipmentController::class, 'duplicate']);

// Admin Tracking History
$router->post('/admin/tracking/add',       [AdminTrackingController::class, 'add']);
$router->post('/admin/tracking/{id}/edit', [AdminTrackingController::class, 'edit']);
$router->post('/admin/tracking/{id}/delete',[AdminTrackingController::class, 'delete']);

// Admin Blog
$router->get('/admin/blog',                [AdminBlogController::class, 'index']);
$router->get('/admin/blog/create',         [AdminBlogController::class, 'create']);
$router->post('/admin/blog/create',        [AdminBlogController::class, 'store']);
$router->get('/admin/blog/{id}/edit',      [AdminBlogController::class, 'edit']);
$router->post('/admin/blog/{id}/edit',     [AdminBlogController::class, 'update']);
$router->post('/admin/blog/{id}/delete',   [AdminBlogController::class, 'delete']);

// Admin Settings
$router->get('/admin/settings',            [AdminSettingsController::class, 'index']);
$router->post('/admin/settings',           [AdminSettingsController::class, 'save']);
$router->get('/admin/websites',            [AdminWebsiteController::class, 'index']);
$router->get('/admin/websites/create',     [AdminWebsiteController::class, 'create']);
$router->post('/admin/websites/create',    [AdminWebsiteController::class, 'store']);
$router->get('/admin/websites/{id}/edit',  [AdminWebsiteController::class, 'edit']);
$router->post('/admin/websites/{id}/edit', [AdminWebsiteController::class, 'update']);
$router->get('/admin/api-keys',            [AdminApiKeyController::class, 'index']);
$router->post('/admin/api-keys/generate',  [AdminApiKeyController::class, 'generate']);
$router->post('/admin/api-keys/{id}/revoke',[AdminApiKeyController::class, 'revoke']);
$router->get('/admin/api-logs',            [AdminApiKeyController::class, 'logs']);
$router->get('/admin/contacts',            [AdminContactController::class, 'index']);
$router->get('/admin/contacts/{id}',       [AdminContactController::class, 'detail']);
$router->post('/admin/contacts/{id}/reply',[AdminContactController::class, 'reply']);
$router->get('/admin/carriers',            [AdminCarrierController::class, 'index']);
$router->post('/admin/carriers',           [AdminCarrierController::class, 'index']);
$router->get('/admin/countries',           [AdminCountryController::class, 'index']);
$router->post('/admin/countries',          [AdminCountryController::class, 'index']);
$router->get('/admin/faq',                 [AdminFaqController::class, 'index']);
$router->post('/admin/faq',                [AdminFaqController::class, 'index']);
$router->get('/admin/partners',            [AdminPartnerController::class, 'index']);
$router->post('/admin/partners',           [AdminPartnerController::class, 'index']);
$router->get('/admin/logs',                [AdminLogController::class, 'index']);
$router->get('/admin/profile',             [AdminProfileController::class, 'index']);
$router->post('/admin/profile',            [AdminProfileController::class, 'update']);

// REST API v1
$router->any('/api/v1/track/{number}',     [ApiTrackingController::class, 'track']);
$router->post('/api/v1/shipments',         [ApiShipmentController::class, 'create']);
$router->get('/api/v1/shipments/{id}',     [ApiShipmentController::class, 'show']);
$router->put('/api/v1/shipments/{id}',     [ApiShipmentController::class, 'update']);
$router->post('/api/v1/shipments/{id}/status',[ApiShipmentController::class, 'updateStatus']);
$router->delete('/api/v1/shipments/{id}',  [ApiShipmentController::class, 'cancel']);
$router->get('/api/v1/shipments/{id}/timeline',[ApiShipmentController::class, 'timeline']);
$router->post('/api/v1/tracking/validate', [ApiShipmentController::class, 'validate']);
$router->get('/api/v1/ping',               [ApiShipmentController::class, 'ping']);

// API Docs
$router->get('/api/docs',                  [ApiDocsController::class, 'index']);

$router->dispatch();
