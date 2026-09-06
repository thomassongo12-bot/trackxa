<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TrackXa API Documentation v1</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{--c-primary:#1a3c6e;--c-accent:#e8a020;--c-bg:#f8f9fa;--c-sidebar:260px;}
body{font-family:'Inter',sans-serif;background:var(--c-bg);color:#2d3748;}
.api-sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--c-sidebar);background:var(--c-primary);overflow-y:auto;z-index:100;padding:1.5rem 0;}
.api-sidebar .brand{padding:.5rem 1.5rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.1);}
.api-sidebar .brand h5{color:#fff;font-weight:800;margin:0;}
.api-sidebar .brand small{color:rgba(255,255,255,.5);font-size:.75rem;}
.api-sidebar ul{list-style:none;padding:1rem 0;margin:0;}
.api-sidebar ul li a{display:flex;align-items:center;gap:.6rem;padding:.55rem 1.5rem;color:rgba(255,255,255,.65);font-size:.85rem;transition:all .2s;text-decoration:none;}
.api-sidebar ul li a:hover,.api-sidebar ul li a.active{color:#fff;background:rgba(255,255,255,.1);}
.api-sidebar .nav-section{padding:.8rem 1.5rem .3rem;font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.3);font-weight:700;}
.api-main{margin-left:var(--c-sidebar);padding:2.5rem;}
.endpoint-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.07);border:1px solid #e2e8f0;margin-bottom:1.5rem;overflow:hidden;}
.endpoint-header{display:flex;align-items:center;gap:1rem;padding:1.1rem 1.5rem;cursor:pointer;border-bottom:1px solid #f1f5f9;}
.endpoint-header:hover{background:#fafbfc;}
.method-badge{display:inline-flex;align-items:center;justify-content:center;padding:.3rem .8rem;border-radius:6px;font-size:.75rem;font-weight:800;font-family:'JetBrains Mono',monospace;min-width:70px;}
.method-get{background:#d1ecf1;color:#0c5460;}
.method-post{background:#d4edda;color:#155724;}
.method-put{background:#fff3cd;color:#856404;}
.method-delete{background:#f8d7da;color:#721c24;}
.endpoint-path{font-family:'JetBrains Mono',monospace;font-size:.9rem;color:var(--c-primary);font-weight:600;}
.endpoint-desc{margin-left:auto;font-size:.82rem;color:#888;}
.endpoint-body{padding:1.5rem;display:none;}
.endpoint-body.open{display:block;}
pre{background:#1e2433;color:#e2e8f0;border-radius:10px;padding:1.2rem;font-family:'JetBrains Mono',monospace;font-size:.8rem;line-height:1.6;overflow-x:auto;}
.param-table{width:100%;border-collapse:collapse;font-size:.85rem;}
.param-table th{background:#f8f9fa;color:#888;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:.6rem 1rem;border-bottom:2px solid #e2e8f0;}
.param-table td{padding:.7rem 1rem;border-bottom:1px solid #f1f5f9;vertical-align:top;}
.param-table tr:last-child td{border:none;}
.required-badge{background:#fde8e8;color:#c53030;font-size:.68rem;font-weight:700;padding:.1rem .5rem;border-radius:4px;text-transform:uppercase;}
.optional-badge{background:#e8f4fd;color:#2b6cb0;font-size:.68rem;font-weight:700;padding:.1rem .5rem;border-radius:4px;text-transform:uppercase;}
.type-badge{background:#f0fff4;color:#276749;font-family:'JetBrains Mono',monospace;font-size:.72rem;padding:.1rem .5rem;border-radius:4px;}
.section-title{font-size:1.6rem;font-weight:800;color:var(--c-primary);margin-bottom:1rem;}
.section-intro{color:#64748b;font-size:.95rem;margin-bottom:2rem;line-height:1.7;}
@media(max-width:991px){.api-sidebar{display:none;}.api-main{margin-left:0;}}
</style>
</head>
<body>

<aside class="api-sidebar">
  <div class="brand">
    <h5>Track<span style="color:var(--c-accent)">Xa</span> API</h5>
    <small>REST API · Version 1.0</small>
    <!-- Lang switcher -->
    <?php
    $apiLocale = $_SESSION['admin_lang'] ?? $_SESSION['lang'] ?? 'en';
    $apiLocale = in_array($apiLocale, ['en','fr']) ? $apiLocale : 'en';
    $baseUrl = ((!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http').'://'.$_SERVER['HTTP_HOST'];
    ?>
    <div style="margin-top:.8rem;display:flex;gap:.5rem">
      <a href="<?= $baseUrl ?>/trackxa/public/admin/lang/en" style="font-size:.75rem;padding:.2rem .6rem;border-radius:4px;background:<?= $apiLocale==='en'?'rgba(232,160,32,.3)':'rgba(255,255,255,.1)' ?>;color:#fff;text-decoration:none">🇬🇧 EN</a>
      <a href="<?= $baseUrl ?>/trackxa/public/admin/lang/fr" style="font-size:.75rem;padding:.2rem .6rem;border-radius:4px;background:<?= $apiLocale==='fr'?'rgba(232,160,32,.3)':'rgba(255,255,255,.1)' ?>;color:#fff;text-decoration:none">🇫🇷 FR</a>
    </div>
  </div>
  <?php
  $t = $apiLocale === 'fr' ? [
    'getting_started' => 'Démarrage',
    'overview'        => 'Vue d\'ensemble',
    'authentication'  => 'Authentification',
    'errors'          => 'Codes d\'erreur',
    'rate_limiting'   => 'Limitation débit',
    'endpoints'       => 'Endpoints',
    'health'          => 'Santé API',
    'track'           => 'Suivre un colis',
    'create'          => 'Créer expédition',
    'get'             => 'Obtenir expédition',
    'update'          => 'Modifier expédition',
    'status'          => 'Mettre à jour statut',
    'timeline'        => 'Historique',
    'cancel'          => 'Annuler expédition',
    'validate'        => 'Valider n° suivi',
    'webhooks_label'  => 'Webhooks',
    'statuses_label'  => 'Statuts disponibles',
  ] : [
    'getting_started' => 'Getting Started',
    'overview'        => 'Overview',
    'authentication'  => 'Authentication',
    'errors'          => 'Error Codes',
    'rate_limiting'   => 'Rate Limiting',
    'endpoints'       => 'Endpoints',
    'health'          => 'Health Check',
    'track'           => 'Track Shipment',
    'create'          => 'Create Shipment',
    'get'             => 'Get Shipment',
    'update'          => 'Update Shipment',
    'status'          => 'Update Status',
    'timeline'        => 'Get Timeline',
    'cancel'          => 'Cancel Shipment',
    'validate'        => 'Validate Tracking',
    'webhooks_label'  => 'Webhooks',
    'statuses_label'  => 'Shipment Statuses',
  ];
  ?>
  <ul>
    <li class="nav-section"><?= $t['getting_started'] ?></li>
    <li><a href="#overview" class="active"><i class="fas fa-book-open"></i> <?= $t['overview'] ?></a></li>
    <li><a href="#authentication"><i class="fas fa-key"></i> <?= $t['authentication'] ?></a></li>
    <li><a href="#errors"><i class="fas fa-circle-xmark"></i> <?= $t['errors'] ?></a></li>
    <li><a href="#rate-limiting"><i class="fas fa-gauge-high"></i> <?= $t['rate_limiting'] ?></a></li>
    <li class="nav-section"><?= $t['endpoints'] ?></li>
    <li><a href="#ping"><i class="fas fa-heart-pulse"></i> <?= $t['health'] ?></a></li>
    <li><a href="#track"><i class="fas fa-magnifying-glass"></i> <?= $t['track'] ?></a></li>
    <li><a href="#create"><i class="fas fa-plus-circle"></i> <?= $t['create'] ?></a></li>
    <li><a href="#get"><i class="fas fa-eye"></i> <?= $t['get'] ?></a></li>
    <li><a href="#update"><i class="fas fa-pen"></i> <?= $t['update'] ?></a></li>
    <li><a href="#status"><i class="fas fa-rotate"></i> <?= $t['status'] ?></a></li>
    <li><a href="#timeline"><i class="fas fa-timeline"></i> <?= $t['timeline'] ?></a></li>
    <li><a href="#cancel"><i class="fas fa-ban"></i> <?= $t['cancel'] ?></a></li>
    <li><a href="#validate"><i class="fas fa-check-circle"></i> <?= $t['validate'] ?></a></li>
    <li class="nav-section">Webhooks</li>
    <li><a href="#webhooks"><i class="fas fa-webhook"></i> <?= $t['webhooks_label'] ?></a></li>
  </ul>
</aside>

<main class="api-main">
  <!-- Overview -->
  <section id="overview" class="mb-5">
    <?php if ($apiLocale === 'fr'): ?>
    <h1 class="section-title"><i class="fas fa-book-open me-2 text-warning"></i>Vue d'ensemble de l'API</h1>
    <p class="section-intro">
      L'API REST TrackXa vous permet de créer des expéditions, mettre à jour les statuts de suivi et récupérer les informations de tracking de manière programmatique. Toutes les réponses sont au format JSON.
      <br><strong>URL de base :</strong> <code><?php echo ((!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http').'://'.$_SERVER['HTTP_HOST']; ?>/api/v1</code>
    </p>
    <?php else: ?>
    <h1 class="section-title"><i class="fas fa-book-open me-2 text-warning"></i>API Overview</h1>
    <p class="section-intro">
      The TrackXa REST API allows you to programmatically create shipments, update tracking statuses, and retrieve tracking information. All responses are in JSON format.
      <br><strong>Base URL:</strong> <code><?php echo ((!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http').'://'.$_SERVER['HTTP_HOST']; ?>/api/v1</code>
    </p>
    <?php endif; ?>
    <div class="endpoint-card p-4">
      <h6 style="font-weight:700;color:var(--c-primary)">Quick Start (PHP)</h6>
      <pre>$client = new GuzzleHttp\Client();
$response = $client->post('BASE_URL/api/v1/shipments', [
    'headers' => ['X-API-Key' => 'your_api_key_here'],
    'json'    => [
        'recipient_name'    => 'John Doe',
        'recipient_city'    => 'Paris',
        'recipient_address' => '10 Rue de la Paix',
        'origin_city'       => 'Casablanca',
        'destination_city'  => 'Paris',
        'weight'            => 2.5,
    ]
]);
echo json_decode($response->getBody());</pre>
    </div>
  </section>

  <!-- Authentication -->
  <section id="authentication" class="mb-5">
    <h2 class="section-title"><i class="fas fa-key me-2 text-warning"></i>Authentication</h2>
    <p class="section-intro">All API requests (except the public track endpoint) require authentication via an API key. Pass your key in the request header.</p>
    <div class="endpoint-card p-4">
      <h6 style="font-weight:700;color:var(--c-primary)">Header Method (Recommended)</h6>
      <pre>X-API-Key: your_api_key_here</pre>
      <h6 style="font-weight:700;color:var(--c-primary);margin-top:1rem">Bearer Token</h6>
      <pre>Authorization: Bearer your_api_key_here</pre>
      <h6 style="font-weight:700;color:var(--c-primary);margin-top:1rem">Query Parameter</h6>
      <pre>GET /api/v1/shipments/123?api_key=your_api_key_here</pre>
    </div>
  </section>

  <!-- Errors -->
  <section id="errors" class="mb-5">
    <h2 class="section-title"><i class="fas fa-circle-xmark me-2 text-warning"></i>Error Codes</h2>
    <div class="endpoint-card">
      <table class="param-table">
        <thead><tr><th>HTTP Code</th><th>Meaning</th><th>Description</th></tr></thead>
        <tbody>
          <tr><td><code>200</code></td><td>OK</td><td>Request succeeded.</td></tr>
          <tr><td><code>201</code></td><td>Created</td><td>Shipment created successfully.</td></tr>
          <tr><td><code>400</code></td><td>Bad Request</td><td>Missing or invalid parameters.</td></tr>
          <tr><td><code>401</code></td><td>Unauthorized</td><td>Invalid or missing API key.</td></tr>
          <tr><td><code>404</code></td><td>Not Found</td><td>Shipment not found.</td></tr>
          <tr><td><code>422</code></td><td>Unprocessable</td><td>Validation error. Check field requirements.</td></tr>
          <tr><td><code>429</code></td><td>Too Many Requests</td><td>Rate limit exceeded.</td></tr>
          <tr><td><code>500</code></td><td>Server Error</td><td>Internal server error.</td></tr>
        </tbody>
      </table>
    </div>
    <div class="endpoint-card p-4 mt-3">
      <h6 style="font-weight:700">Error Response Format</h6>
      <pre>{"success": false, "error": "Description of the error.", "code": 401}</pre>
    </div>
  </section>

  <!-- Rate Limiting -->
  <section id="rate-limiting" class="mb-5">
    <h2 class="section-title"><i class="fas fa-gauge-high me-2 text-warning"></i>Rate Limiting</h2>
    <p class="section-intro">API keys are limited to <strong>1,000 requests per day</strong> by default. Contact us to increase your limit. When exceeded, you receive HTTP 429.</p>
  </section>

  <!-- PING -->
  <section id="ping" class="mb-5">
    <h2 class="section-title"><i class="fas fa-heart-pulse me-2 text-warning"></i>Health Check</h2>
    <div class="endpoint-card">
      <div class="endpoint-header" onclick="toggleEndpoint(this)">
        <span class="method-badge method-get">GET</span>
        <span class="endpoint-path">/api/v1/ping</span>
        <span class="endpoint-desc">Check API availability</span>
        <i class="fas fa-chevron-down ms-2 text-muted"></i>
      </div>
      <div class="endpoint-body">
        <p>No authentication required.</p>
        <h6 style="font-weight:700">Response</h6>
        <pre>{"success": true, "message": "TrackXa API v1 is running", "timestamp": 1717000000}</pre>
      </div>
    </div>
  </section>

  <!-- TRACK (Public) -->
  <section id="track" class="mb-5">
    <h2 class="section-title"><i class="fas fa-magnifying-glass me-2 text-warning"></i>Track Shipment (Public)</h2>
    <div class="endpoint-card">
      <div class="endpoint-header" onclick="toggleEndpoint(this)">
        <span class="method-badge method-get">GET</span>
        <span class="endpoint-path">/api/v1/track/{tracking_number}</span>
        <span class="endpoint-desc">Public tracking – no auth required</span>
        <i class="fas fa-chevron-down ms-2 text-muted"></i>
      </div>
      <div class="endpoint-body">
        <p>Returns full tracking info. No API key required. CORS enabled.</p>
        <h6 style="font-weight:700">Path Parameters</h6>
        <table class="param-table mb-3"><thead><tr><th>Parameter</th><th>Type</th><th>Description</th></tr></thead>
        <tbody><tr><td>tracking_number</td><td><span class="type-badge">string</span></td><td>Tracking number, reference, or order number.</td></tr></tbody></table>
        <h6 style="font-weight:700">Response</h6>
        <pre>{
  "success": true,
  "tracking": {
    "tracking_number": "TXA1A2B3C4D5E6",
    "status": "in_transit",
    "status_label": "In Transit",
    "status_color": "primary",
    "current_location": "Paris CDG Airport",
    "origin": "Casablanca",
    "destination": "Paris",
    "shipping_date": "2025-01-15",
    "estimated_delivery": "2025-01-18",
    "actual_delivery": null,
    "carrier": "DHL Express",
    "weight": "2.500",
    "weight_unit": "kg",
    "timeline": [
      {"id": 1, "status": "shipment_created", "location": "Casablanca", "occurred_at": "2025-01-15 09:00:00"},
      {"id": 2, "status": "in_transit", "location": "Paris CDG", "occurred_at": "2025-01-16 14:30:00"}
    ],
    "tracking_url": "https://yoursite.com/track/TXA1A2B3C4D5E6"
  }
}</pre>
      </div>
    </div>
  </section>

  <!-- CREATE -->
  <section id="create" class="mb-5">
    <h2 class="section-title"><i class="fas fa-plus-circle me-2 text-warning"></i>Create Shipment</h2>
    <div class="endpoint-card">
      <div class="endpoint-header" onclick="toggleEndpoint(this)">
        <span class="method-badge method-post">POST</span>
        <span class="endpoint-path">/api/v1/shipments</span>
        <span class="endpoint-desc">Create a new shipment and get tracking number</span>
        <i class="fas fa-chevron-down ms-2 text-muted"></i>
      </div>
      <div class="endpoint-body open">
        <h6 style="font-weight:700">Request Body (JSON)</h6>
        <table class="param-table mb-3">
          <thead><tr><th>Field</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
          <tbody>
            <tr><td>recipient_name</td><td><span class="type-badge">string</span></td><td><span class="required-badge">required</span></td><td>Full name of recipient</td></tr>
            <tr><td>recipient_address</td><td><span class="type-badge">string</span></td><td><span class="required-badge">required</span></td><td>Delivery address</td></tr>
            <tr><td>recipient_city</td><td><span class="type-badge">string</span></td><td><span class="required-badge">required</span></td><td>Destination city</td></tr>
            <tr><td>recipient_email</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>For email notifications</td></tr>
            <tr><td>recipient_phone</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Recipient phone</td></tr>
            <tr><td>sender_name</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Sender full name</td></tr>
            <tr><td>origin_city</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Origin city</td></tr>
            <tr><td>destination_city</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Destination city label</td></tr>
            <tr><td>reference_number</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Your internal reference</td></tr>
            <tr><td>order_number</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>E-commerce order ID</td></tr>
            <tr><td>weight</td><td><span class="type-badge">float</span></td><td><span class="optional-badge">optional</span></td><td>Package weight</td></tr>
            <tr><td>weight_unit</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>kg / lb / g (default: kg)</td></tr>
            <tr><td>estimated_delivery</td><td><span class="type-badge">date</span></td><td><span class="optional-badge">optional</span></td><td>YYYY-MM-DD format</td></tr>
            <tr><td>shipping_date</td><td><span class="type-badge">date</span></td><td><span class="optional-badge">optional</span></td><td>YYYY-MM-DD (default: today)</td></tr>
            <tr><td>declared_value</td><td><span class="type-badge">float</span></td><td><span class="optional-badge">optional</span></td><td>Declared package value</td></tr>
            <tr><td>currency</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>USD, EUR, GBP... (default: USD)</td></tr>
            <tr><td>description</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Package contents description</td></tr>
            <tr><td>special_instructions</td><td><span class="type-badge">string</span></td><td><span class="optional-badge">optional</span></td><td>Delivery instructions</td></tr>
            <tr><td>carrier_id</td><td><span class="type-badge">integer</span></td><td><span class="optional-badge">optional</span></td><td>Carrier ID from /carriers</td></tr>
          </tbody>
        </table>
        <h6 style="font-weight:700">Example Request</h6>
        <pre>POST /api/v1/shipments
X-API-Key: your_api_key
Content-Type: application/json

{
  "recipient_name": "Marie Dupont",
  "recipient_email": "marie@example.com",
  "recipient_phone": "+33612345678",
  "recipient_city": "Lyon",
  "recipient_address": "15 Rue Victor Hugo, Lyon",
  "sender_name": "ParruParrot Store",
  "origin_city": "Paris",
  "destination_city": "Lyon",
  "order_number": "ORD-2025-9912",
  "weight": 1.2,
  "weight_unit": "kg",
  "estimated_delivery": "2025-01-20",
  "declared_value": 89.99,
  "currency": "EUR",
  "description": "Electronics - Wireless Headphones"
}</pre>
        <h6 style="font-weight:700">Response (201)</h6>
        <pre>{
  "success": true,
  "tracking_number": "TXA1A2B3C4D5E6",
  "tracking_url": "https://yoursite.com/track/TXA1A2B3C4D5E6",
  "shipment_id": 42,
  "estimated_delivery": "2025-01-20"
}</pre>
      </div>
    </div>
  </section>

  <!-- GET / UPDATE / STATUS / TIMELINE / CANCEL / VALIDATE -->
  <?php
  $endpoints = [
    ['method'=>'get','id'=>'get','path'=>'/api/v1/shipments/{id}','title'=>'Get Shipment','desc'=>'Retrieve full shipment details.',
     'response'=>'{"success":true,"data":{"id":42,"tracking_number":"TXA1A2B3C4D5E6","status":"in_transit","status_label":"In Transit","carrier":"DHL Express","recipient":{"name":"Marie Dupont","city":"Lyon"},...}}'],
    ['method'=>'put','id'=>'update','path'=>'/api/v1/shipments/{id}','title'=>'Update Shipment','desc'=>'Update shipment details (not status).',
     'response'=>'{"success":true,"message":"Shipment updated."}'],
    ['method'=>'post','id'=>'status','path'=>'/api/v1/shipments/{id}/status','title'=>'Update Status','desc'=>'Add a new tracking status update.',
     'body'=>'{"status":"out_for_delivery","location":"Lyon Delivery Hub","description":"Out for delivery","occurred_at":"2025-01-20 08:00:00"}',
     'response'=>'{"success":true,"message":"Status updated.","status":"out_for_delivery"}'],
    ['method'=>'get','id'=>'timeline','path'=>'/api/v1/shipments/{id}/timeline','title'=>'Get Timeline','desc'=>'Retrieve the full tracking timeline.',
     'response'=>'{"success":true,"tracking_number":"TXA1A2B3C4D5E6","timeline":[{"id":1,"status":"shipment_created","location":"Paris",...},{"id":2,"status":"in_transit",...}]}'],
    ['method'=>'delete','id'=>'cancel','path'=>'/api/v1/shipments/{id}','title'=>'Cancel Shipment','desc'=>'Cancel a shipment.',
     'response'=>'{"success":true,"message":"Shipment cancelled."}'],
    ['method'=>'post','id'=>'validate','path'=>'/api/v1/tracking/validate','title'=>'Validate Tracking Number','desc'=>'Check if a tracking number exists.',
     'body'=>'{"tracking_number":"TXA1A2B3C4D5E6"}',
     'response'=>'{"success":true,"valid":true,"status":"in_transit"}'],
  ];
  foreach ($endpoints as $ep):
    $mc = 'method-'.$ep['method'];
    $ml = strtoupper($ep['method']);
  ?>
  <section id="<?= $ep['id'] ?>" class="mb-4">
    <h2 class="section-title"><?= $ep['title'] ?></h2>
    <div class="endpoint-card">
      <div class="endpoint-header" onclick="toggleEndpoint(this)">
        <span class="method-badge <?= $mc ?>"><?= $ml ?></span>
        <span class="endpoint-path"><?= $ep['path'] ?></span>
        <span class="endpoint-desc"><?= $ep['desc'] ?></span>
        <i class="fas fa-chevron-down ms-2 text-muted"></i>
      </div>
      <div class="endpoint-body">
        <p style="color:#64748b;font-size:.9rem"><?= $ep['desc'] ?></p>
        <?php if (!empty($ep['body'])): ?>
        <h6 style="font-weight:700">Request Body</h6>
        <pre><?= htmlspecialchars($ep['body']) ?></pre>
        <?php endif; ?>
        <h6 style="font-weight:700">Response</h6>
        <pre><?= htmlspecialchars($ep['response']) ?></pre>
      </div>
    </div>
  </section>
  <?php endforeach; ?>

  <!-- WEBHOOKS -->
  <section id="webhooks" class="mb-5">
    <h2 class="section-title"><i class="fas fa-webhook me-2 text-warning"></i>Webhooks</h2>
    <p class="section-intro">TrackXa can send POST requests to your server whenever a shipment event occurs. Configure webhooks in the Admin → Websites panel.</p>
    <div class="endpoint-card p-4">
      <h6 style="font-weight:700">Webhook Events</h6>
      <table class="param-table mb-3">
        <thead><tr><th>Event</th><th>Description</th></tr></thead>
        <tbody>
          <tr><td><code>shipment.created</code></td><td>New shipment created</td></tr>
          <tr><td><code>shipment.updated</code></td><td>Shipment details updated</td></tr>
          <tr><td><code>shipment.status_updated</code></td><td>New tracking status added</td></tr>
          <tr><td><code>shipment.delivered</code></td><td>Shipment marked as delivered</td></tr>
          <tr><td><code>shipment.cancelled</code></td><td>Shipment cancelled</td></tr>
        </tbody>
      </table>
      <h6 style="font-weight:700">Payload Format</h6>
      <pre>{
  "event": "shipment.status_updated",
  "timestamp": 1717000000,
  "data": {
    "tracking_number": "TXA1A2B3C4D5E6",
    "status": "out_for_delivery",
    "location": "Lyon Delivery Hub"
  }
}</pre>
      <h6 style="font-weight:700 mt-3">Verifying Signature</h6>
      <pre>// PHP example
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_TRACKXA_SIGNATURE'];
$secret    = 'your_webhook_secret';
$expected  = hash_hmac('sha256', $payload, $secret);
if (!hash_equals($expected, $signature)) { die('Invalid signature'); }</pre>
    </div>
  </section>

  <!-- Status Codes Reference -->
  <section id="statuses" class="mb-5">
    <h2 class="section-title"><i class="fas fa-list-check me-2 text-warning"></i>Shipment Statuses</h2>
    <div class="endpoint-card">
      <table class="param-table">
        <thead><tr><th>Status Code</th><th>Label</th></tr></thead>
        <tbody>
          <?php
          $statuses=[
            'order_received'=>'Order Received','shipment_created'=>'Shipment Created','preparing'=>'Preparing Shipment',
            'picked_up'=>'Picked Up','at_warehouse'=>'At Warehouse','in_transit'=>'In Transit',
            'arrived_airport'=>'Arrived at Airport','departed_airport'=>'Departed Airport',
            'customs_clearance'=>'Customs Clearance','released_customs'=>'Released from Customs',
            'out_for_delivery'=>'Out for Delivery','delivered'=>'Delivered','delivery_failed'=>'Delivery Failed',
            'returned'=>'Returned to Sender','cancelled'=>'Cancelled','delayed'=>'Delayed','on_hold'=>'On Hold',
          ];
          foreach ($statuses as $k=>$v): ?>
          <tr><td><code><?= $k ?></code></td><td><?= $v ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleEndpoint(header){
  var body=header.nextElementSibling;
  var icon=header.querySelector('.fa-chevron-down,.fa-chevron-up');
  body.classList.toggle('open');
  if(icon){icon.className=body.classList.contains('open')?'fas fa-chevron-up ms-2 text-muted':'fas fa-chevron-down ms-2 text-muted';}
}
document.querySelectorAll('.api-sidebar a').forEach(function(a){
  a.addEventListener('click',function(e){
    document.querySelectorAll('.api-sidebar a').forEach(function(x){x.classList.remove('active');});
    a.classList.add('active');
  });
});
</script>
</body>
</html>
