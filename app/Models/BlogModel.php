<?php
class BlogModel extends Model {
    protected string $table = 'blog_posts';

    public function getPublished(int $page = 1, int $per = 9): array {
        $where  = "status = 'published' AND published_at <= NOW()";
        return $this->paginate($page, $per, $where, [], 'published_at DESC');
    }

    public function getBySlug(string $slug): array|false {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, a.name AS author_name
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            LEFT JOIN admins a ON p.admin_id = a.id
            WHERE p.slug = ? AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([$slug]);
        $post = $stmt->fetch();
        if ($post) {
            $this->db->prepare("UPDATE blog_posts SET views = views+1 WHERE id = ?")->execute([$post['id']]);
        }
        return $post;
    }

    public function getByCategory(string $catSlug, int $page = 1, int $per = 9): array {
        $stmt = $this->db->prepare("SELECT id FROM blog_categories WHERE slug = ? LIMIT 1");
        $stmt->execute([$catSlug]);
        $cat = $stmt->fetch();
        if (!$cat) return ['data' => [], 'total' => 0, 'pages' => 0, 'page' => 1, 'per_page' => $per];
        $where = "status = 'published' AND published_at <= NOW() AND category_id = ?";
        return $this->paginate($page, $per, $where, [$cat['id']], 'published_at DESC');
    }

    public function getRelated(int $catId, int $excludeId, int $limit = 3): array {
        return $this->query("
            SELECT * FROM blog_posts WHERE category_id = ? AND id != ? AND status='published' ORDER BY published_at DESC LIMIT ?
        ", [$catId, $excludeId, $limit])->fetchAll();
    }

    public function getFeatured(int $limit = 3): array {
        return $this->query("
            SELECT p.*, c.name AS category_name
            FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.status='published' AND p.published_at <= NOW()
            ORDER BY p.views DESC LIMIT ?
        ", [$limit])->fetchAll();
    }
}
