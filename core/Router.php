<?php
/**
 * TrackXa - Router
 */
class Router {
    private array $routes = [];
    private string $basePath = '';

    public function add(string $method, string $pattern, array $handler): void {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $this->compilePattern($pattern),
            'handler' => $handler,
        ];
    }

    public function get(string $pattern, array $handler): void    { $this->add('GET',    $pattern, $handler); }
    public function post(string $pattern, array $handler): void   { $this->add('POST',   $pattern, $handler); }
    public function put(string $pattern, array $handler): void    { $this->add('PUT',    $pattern, $handler); }
    public function delete(string $pattern, array $handler): void { $this->add('DELETE', $pattern, $handler); }
    public function any(string $pattern, array $handler): void    {
        foreach (['GET','POST','PUT','DELETE','PATCH'] as $m) { $this->add($m, $pattern, $handler); }
    }

    private function compilePattern(string $pattern): string {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = preg_replace('/\{(\w+):\s*([^}]+)\}/', '(?P<$1>$2)', $pattern);
        return '#^' . $pattern . '$#u';
    }

    public function dispatch(): void {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri    = rawurldecode($uri);
        // Strip script path
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }
        $uri = '/' . ltrim($uri, '/');
        if ($uri !== '/' ) $uri = rtrim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method && $route['method'] !== 'ANY') continue;
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);
                [$class, $action] = $route['handler'];
                $controller = new $class();
                $controller->$action($params);
                return;
            }
        }
        http_response_code(404);
        require APP_PATH . '/Views/errors/404.php';
    }
}
