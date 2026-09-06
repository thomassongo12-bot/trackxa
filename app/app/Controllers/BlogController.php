<?php
class BlogController extends Controller {
    private BlogModel $blog;

    public function __construct() { $this->blog = new BlogModel(); }

    public function index(array $params = []): void {
        $page  = max(1, (int)($this->get('page', 1)));
        $data  = $this->blog->getPublished($page, 9);
        $db    = Database::getInstance();
        $stmt  = $db->query("SELECT * FROM blog_categories WHERE status=1 ORDER BY name");
        $cats  = $stmt->fetchAll();
        $this->view('public.blog', [
            'title'      => 'Blog – ' . Settings::get('site_name', 'TrackXa'),
            'posts'      => $data['data'],
            'pagination' => $data,
            'categories' => $cats,
        ]);
    }

    public function show(array $params = []): void {
        $post = $this->blog->getBySlug($params['slug'] ?? '');
        if (!$post) { $this->notFound(); }
        $related = $this->blog->getRelated($post['category_id'] ?? 0, $post['id']);
        $this->view('public.blog_post', [
            'title'       => ($post['meta_title'] ?: $post['title']) . ' – ' . Settings::get('site_name', 'TrackXa'),
            'description' => $post['meta_description'] ?? $post['excerpt'],
            'keywords'    => $post['meta_keywords'] ?? '',
            'ogImage'     => $post['og_image'] ?: $post['featured_image'],
            'post'        => $post,
            'related'     => $related,
        ]);
    }

    public function category(array $params = []): void {
        $page = max(1, (int)$this->get('page', 1));
        $data = $this->blog->getByCategory($params['slug'] ?? '', $page, 9);
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM blog_categories WHERE slug = ? LIMIT 1");
        $stmt->execute([$params['slug'] ?? '']);
        $cat  = $stmt->fetch();
        if (!$cat) { $this->notFound(); }
        $this->view('public.blog', [
            'title'      => $cat['name'] . ' – Blog – ' . Settings::get('site_name', 'TrackXa'),
            'posts'      => $data['data'],
            'pagination' => $data,
            'categories' => [],
            'currentCat' => $cat,
        ]);
    }
}
