<?php
/**
 * TrackXa – Date Helper
 */
class DateHelper {
    public static function humanDiff(string $datetime): string {
        $diff = time() - strtotime($datetime);
        if ($diff < 60)     return 'just now';
        if ($diff < 3600)   return floor($diff/60)   . ' min ago';
        if ($diff < 86400)  return floor($diff/3600)  . ' hr ago';
        if ($diff < 604800) return floor($diff/86400) . ' days ago';
        return date('M d, Y', strtotime($datetime));
    }

    public static function formatDate(string $date, string $format = 'M d, Y'): string {
        return date($format, strtotime($date));
    }

    public static function isOverdue(string $estimatedDelivery, string $currentStatus): bool {
        if (in_array($currentStatus, ['delivered','cancelled','returned'])) return false;
        return strtotime($estimatedDelivery) < time();
    }

    public static function daysUntil(string $date): int {
        return (int)ceil((strtotime($date) - time()) / 86400);
    }
}
