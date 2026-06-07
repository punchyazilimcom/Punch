<?php

namespace App\Core;

/**
 * Hafif router. Pattern: /blog/{slug}, /admin/paketler/{id}
 * Handler: 'App\Controllers\HomeController@index' veya closure.
 */
class Router
{
    /** @var array<string, array<int, array{pattern:string, regex:string, params:array, handler:mixed, middleware:array}>> */
    private array $routes = [];
    private array $groupStack = [];

    public function get(string $pattern, mixed $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, mixed $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function any(array $methods, string $pattern, mixed $handler, array $middleware = []): void
    {
        foreach ($methods as $m) {
            $this->add($m, $pattern, $handler, $middleware);
        }
    }

    /**
     * Ortak prefix + middleware ile grup.
     */
    public function group(string $prefix, array $middleware, callable $callback): void
    {
        $this->groupStack[] = ['prefix' => $prefix, 'middleware' => $middleware];
        $callback($this);
        array_pop($this->groupStack);
    }

    private function add(string $method, string $pattern, mixed $handler, array $middleware): void
    {
        $prefix = '';
        $groupMw = [];
        foreach ($this->groupStack as $g) {
            $prefix .= $g['prefix'];
            $groupMw = array_merge($groupMw, $g['middleware']);
        }
        $full = $prefix . $pattern;
        if ($full !== '/' && str_ends_with($full, '/')) {
            $full = rtrim($full, '/');
        }
        $regex = $this->compile($full);
        $this->routes[$method][] = [
            'pattern'    => $full,
            'regex'      => $regex['regex'],
            'params'     => $regex['params'],
            'handler'    => $handler,
            'middleware' => array_merge($groupMw, $middleware),
        ];
    }

    private function compile(string $pattern): array
    {
        $params = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function ($m) use (&$params) {
            $params[] = $m[1];
            return '([^/]+)';
        }, $pattern);
        return ['regex' => '#^' . $regex . '$#', 'params' => $params];
    }

    public function dispatch(Request $request): mixed
    {
        $method = $request->method === 'HEAD' ? 'GET' : $request->method;
        $candidates = $this->routes[$method] ?? [];

        foreach ($candidates as $route) {
            if (preg_match($route['regex'], $request->path, $matches)) {
                array_shift($matches);
                $params = array_combine($route['params'], $matches) ?: [];

                // Middleware zinciri
                foreach ($route['middleware'] as $mw) {
                    (new $mw())->handle($request);
                }

                return $this->call($route['handler'], $request, $params);
            }
        }

        // 404
        return $this->notFound($request);
    }

    private function call(mixed $handler, Request $request, array $params): mixed
    {
        if (is_callable($handler)) {
            return $handler($request, $params);
        }
        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $action] = explode('@', $handler, 2);
            $controller = new $class();
            return $controller->$action($request, $params);
        }
        throw new \RuntimeException('Gecersiz route handler.');
    }

    private function notFound(Request $request): never
    {
        if ($request->wantsJson()) {
            Response::json(['error' => 'Bulunamadi'], 404);
        }
        $view = new View();
        Response::securityHeaders();
        Response::html($view->render('errors/404', ['title' => 'Sayfa Bulunamadi'], 'layouts/error'), 404);
    }
}
