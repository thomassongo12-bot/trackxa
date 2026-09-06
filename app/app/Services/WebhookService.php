<?php
/**
 * TrackXa - Webhook Service
 */
class WebhookService {

    public static function dispatch(int $websiteId, string $event, array $payload): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM webhooks WHERE website_id = ? AND is_active = 1");
        $stmt->execute([$websiteId]);
        $hooks = $stmt->fetchAll();

        foreach ($hooks as $hook) {
            $events = json_decode($hook['events'] ?? '[]', true);
            if (!empty($events) && !in_array($event, $events)) continue;
            self::fire($hook, $event, $payload);
        }
    }

    private static function fire(array $hook, string $event, array $payload): void {
        $body = json_encode([
            'event'     => $event,
            'timestamp' => time(),
            'data'      => $payload,
        ]);

        $sig = hash_hmac('sha256', $body, $hook['secret'] ?? '');

        $ch = curl_init($hook['url']);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'X-TrackXa-Signature: ' . $sig,
                'X-TrackXa-Event: ' . $event,
            ],
        ]);

        $response = curl_exec($ch);
        $code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        $status = ($code >= 200 && $code < 300) ? 'success' : 'failed';

        Database::getInstance()->prepare("
            INSERT INTO webhook_logs (webhook_id, event, payload, response_code, response_body, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ")->execute([$hook['id'], $event, $body, $code, $response ?: $error, $status]);

        Database::getInstance()->prepare("UPDATE webhooks SET last_triggered_at = NOW() WHERE id = ?")
            ->execute([$hook['id']]);
    }
}
