<?php
class SeoController extends Controller {
    public function sitemap(array $params = []): void {
        header('Content-Type: application/xml; charset=utf-8');
        $db = Database::getInstance();
        $posts = $db->query("SELECT slug, updated_at FROM blog_posts WHERE status='published'")->fetchAll();
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $pages = ['/', '/track', '/blog', '/contact', '/faq'];
        foreach ($pages as $p) {
            echo "<url><loc>" . BASE_URL . $p . "</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>\n";
        }
        foreach ($posts as $post) {
            $date = date('Y-m-d', strtotime($post['updated_at']));
            echo "<url><loc>" . BASE_URL . "/blog/" . Security::e($post['slug']) . "</loc><lastmod>{$date}</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>\n";
        }
        echo '</urlset>';
        exit;
    }

    public function robots(array $params = []): void {
        header('Content-Type: text/plain');
        echo "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\n\nSitemap: " . BASE_URL . "/sitemap.xml\n";
        exit;
    }
}
