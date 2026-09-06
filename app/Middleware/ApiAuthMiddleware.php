<?php
/**
 * TrackXa - API Authentication Middleware
 */
class ApiAuthMiddleware {
    private ApiKeyModel $keyModel;

    public function __construct() { $this->keyModel = new ApiKeyModel(); }

    public function handle(): array {
        $start  = microtime(true);
        $apiKey = $_SERVER['HTTP_X_API_KEY']
               ?? $_SERVER['HTTP_AUTHORIZATION']
               ?? $this->getBearerToken()
               ?? $_GET['api_key']
               ?? null;

        if (!$apiKey) {
            $this->abort(401, 'API key required. Pass X-API-Key header.');
        }

        $apiKey = str_replace('Bearer ', '', $apiKey);
        $keyRow = $this->keyModel->findByKey($apiKey);

        if (!$keyRow) {
            $this->abort(401, 'Invalid or inactive API key.');
        }

        // Rate limit
        if (!$this->keyModel->checkRateLimit($keyRow['id'], $keyRow['rate_limit'])) {
            $this->abort(429, 'Rate limit exceeded. Try again tomorrow.');
        }

        $this->keyModel->incrementUsage($keyRow['id']);

        return ['key' => $keyRow, 'start' => $start];
    }

    private function getBearerToken(): ?string {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($auth, 'Bearer ')) {
            return trim(substr($auth, 7));
        }
        return null;
    }

    private function abort(int $code, string $message): never {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $message, 'code' => $code]);
        exit;
    }
}
