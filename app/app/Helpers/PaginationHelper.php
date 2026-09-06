<?php
/**
 * TrackXa – Pagination Helper
 */
class PaginationHelper {
    public static function render(int $currentPage, int $totalPages, string $baseUrl, array $extraParams = []): string {
        if ($totalPages <= 1) return '';
        $query  = $extraParams ? '&' . http_build_query($extraParams) : '';
        $html   = '<nav><ul class="pagination tx-pagination mb-0 flex-wrap">';
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage-1) . $query . '">&laquo;</a></li>';
        }
        $start = max(1, $currentPage - 2);
        $end   = min($totalPages, $currentPage + 2);
        if ($start > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1' . $query . '">1</a></li>';
            if ($start > 2) $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        for ($p = $start; $p <= $end; $p++) {
            $active = $p === $currentPage ? ' active' : '';
            $html  .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $p . $query . '">' . $p . '</a></li>';
        }
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $totalPages . $query . '">' . $totalPages . '</a></li>';
        }
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage+1) . $query . '">&raquo;</a></li>';
        }
        $html .= '</ul></nav>';
        return $html;
    }
}
