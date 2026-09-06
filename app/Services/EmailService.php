<?php
/**
 * TrackXa - Email Service (PHPMailer-free, uses PHP mail() or SMTP via sockets)
 * For production, integrate PHPMailer via composer.
 */
class EmailService {

    public static function send(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool {
        $from    = Settings::get('site_email', 'noreply@trackxa.com');
        $siteName= Settings::get('site_name', 'TrackXa');

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$siteName} <{$from}>\r\n";
        $headers .= "Reply-To: {$from}\r\n";
        $headers .= "X-Mailer: TrackXa/1.0\r\n";

        $sent = @mail($to, $subject, $htmlBody, $headers);

        // Log it
        try {
            Database::getInstance()->prepare(
                "INSERT INTO email_logs (to_email,subject,body,status) VALUES (?,?,?,?)"
            )->execute([$to, $subject, $htmlBody, $sent ? 'sent' : 'failed']);
        } catch (Exception $e) {}

        return $sent;
    }

    public static function shipmentCreated(array $shipment): bool {
        if (empty($shipment['recipient_email'])) return false;
        $siteName = Settings::get('site_name', 'TrackXa');
        $trackUrl = BASE_URL . '/track/' . $shipment['tracking_number'];
        $subject  = "Your shipment has been created – {$shipment['tracking_number']}";
        $body = self::getTemplate('shipment_created', [
            'site_name'        => $siteName,
            'tracking_number'  => $shipment['tracking_number'],
            'recipient_name'   => $shipment['recipient_name'],
            'tracking_url'     => $trackUrl,
            'estimated_delivery'=> $shipment['estimated_delivery'] ?? 'TBD',
        ]);
        return self::send($shipment['recipient_email'], $subject, $body);
    }

    public static function shipmentUpdated(array $shipment, string $status): bool {
        if (empty($shipment['recipient_email'])) return false;
        $statuses = ShipmentModel::STATUSES;
        $label    = $statuses[$status]['label'] ?? $status;
        $siteName = Settings::get('site_name', 'TrackXa');
        $trackUrl = BASE_URL . '/track/' . $shipment['tracking_number'];
        $subject  = "Shipment Update: {$label} – {$shipment['tracking_number']}";
        $body = self::getTemplate('shipment_update', [
            'site_name'       => $siteName,
            'tracking_number' => $shipment['tracking_number'],
            'recipient_name'  => $shipment['recipient_name'],
            'status_label'    => $label,
            'tracking_url'    => $trackUrl,
        ]);
        return self::send($shipment['recipient_email'], $subject, $body);
    }

    private static function getTemplate(string $name, array $vars): string {
        $file = APP_PATH . '/Views/emails/' . $name . '.php';
        if (!file_exists($file)) {
            return self::genericTemplate($vars);
        }
        ob_start();
        extract($vars);
        require $file;
        return ob_get_clean();
    }

    private static function genericTemplate(array $vars): string {
        $siteName = $vars['site_name'] ?? 'TrackXa';
        $body     = '';
        foreach ($vars as $k => $v) {
            if (!is_array($v)) $body .= "<p><strong>{$k}:</strong> {$v}</p>";
        }
        return "<!DOCTYPE html><html><body style='font-family:sans-serif;max-width:600px;margin:auto'>
            <div style='background:#1a3c6e;padding:20px;text-align:center'>
                <h1 style='color:white;margin:0'>{$siteName}</h1>
            </div>
            <div style='padding:30px'>{$body}</div>
            <div style='background:#f4f4f4;padding:15px;text-align:center;font-size:12px;color:#666'>
                &copy; " . date('Y') . " {$siteName}
            </div></body></html>";
    }
}
