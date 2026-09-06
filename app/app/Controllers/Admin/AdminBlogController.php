<?php
class AdminBlogController extends Controller {
    private BlogModel $blog;

    public function __construct() { $this->blog = new BlogModel(); }

    public function index(array $params = []): void {
        $this->requireAdmin();
        $page   = max(1, (int)$this->get('page', 1));
        $result = $this->blog->paginate($page, 15, '', [], 'created_at DESC');
        $db     = Database::getInstance();
        $cats   = $db->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
        $this->view('admin.blog.index', ['title' => 'Blog – Admin', 'result' => $result, 'categories' => $cats], 'admin');
    }

    public function create(array $params = []): void {
        $this->requireAdmin();
        $db   = Database::getInstance();
        $cats = $db->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
        $this->view('admin.blog.form', ['title' => 'Create Post – Admin', 'post' => null, 'categories' => $cats], 'admin');
    }

    public function store(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $data = $this->collectData();
        $data['admin_id'] = Session::getInstance()->get('admin_id');

        // Featured image upload
        if (!empty($_FILES['featured_image']['name'])) {
            $path = UploadService::image($_FILES['featured_image'], 'blog');
            if ($path) $data['featured_image'] = $path;
        }

        $this->blog->insert($data);
        Session::getInstance()->flash('success', 'Post created.');
        $this->redirect(BASE_URL . '/admin/blog');
    }

    public function edit(array $params = []): void {
        $this->requireAdmin();
        $post = $this->blog->find((int)$params['id']);
        if (!$post) $this->notFound();
        $db   = Database::getInstance();
        $cats = $db->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
        $this->view('admin.blog.form', ['title' => 'Edit Post – Admin', 'post' => $post, 'categories' => $cats], 'admin');
    }

    public function update(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id   = (int)$params['id'];
        $post = $this->blog->find($id);
        if (!$post) $this->notFound();
        $data = $this->collectData();

        if (!empty($_FILES['featured_image']['name'])) {
            $path = UploadService::image($_FILES['featured_image'], 'blog');
            if ($path) {
                if ($post['featured_image']) UploadService::delete($post['featured_image']);
                $data['featured_image'] = $path;
            }
        }

        $this->blog->update($id, $data);
        Session::getInstance()->flash('success', 'Post updated.');
        $this->redirect(BASE_URL . '/admin/blog/' . $id . '/edit');
    }

    public function delete(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id   = (int)$params['id'];
        $post = $this->blog->find($id);
        if ($post && $post['featured_image']) UploadService::delete($post['featured_image']);
        $this->blog->delete($id);
        Session::getInstance()->flash('success', 'Post deleted.');
        $this->redirect(BASE_URL . '/admin/blog');
    }

    private function collectData(): array {
        $title       = $this->post('title', '');
        $slug        = $this->generateSlug($title);
        $status      = $this->post('status', 'draft');
        $publishedAt = null;
        if ($status === 'published' && !$this->post('published_at')) {
            $publishedAt = date('Y-m-d H:i:s');
        } elseif ($this->post('published_at')) {
            $publishedAt = $this->post('published_at');
        }
        return [
            'category_id'      => $this->post('category_id') ?: null,
            'title'            => $title,
            'slug'             => $slug,
            'excerpt'          => $this->post('excerpt'),
            'content'          => $_POST['content'] ?? '', // allow HTML
            'tags'             => $this->post('tags'),
            'meta_title'       => $this->post('meta_title'),
            'meta_description' => $this->post('meta_description'),
            'meta_keywords'    => $this->post('meta_keywords'),
            'status'           => $status,
            'published_at'     => $publishedAt,
        ];
    }

    private function generateSlug(string $title): string {
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
        // Ensure uniqueness
        $db   = Database::getInstance();
        $base = $slug;
        $i    = 1;
        while ($db->prepare("SELECT id FROM blog_posts WHERE slug=?")->execute([$slug]) &&
               $db->query("SELECT COUNT(*) FROM blog_posts WHERE slug='{$slug}'")->fetchColumn() > 0) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
