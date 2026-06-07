<?php

namespace App\Core;

/**
 * Saf PHP sablon motoru. Layout + partial + section destekli, auto-escape helper'lari ile.
 */
class View
{
    private string $basePath;
    private array $sections = [];
    private array $sectionStack = [];
    private array $shared = [];

    public function __construct()
    {
        $this->basePath = dirname(__DIR__) . '/Views';
        // Tum sablonlarda erisilebilen ortak veriler
        $this->shared = [
            'app'      => App::get()->config['app'],
            'company'  => App::get()->config['company'],
            'analytics'=> App::get()->config['analytics'],
        ];
    }

    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }

    /**
     * Bir sayfayi (opsiyonel) layout icinde render eder ve string dondurur.
     */
    public function render(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $data = array_merge($this->shared, $data);
        $content = $this->renderFile($template, $data);

        if ($layout === null) {
            return $content;
        }

        $data['content'] = $content;
        $data['__view'] = $this;
        return $this->renderFile($layout, $data);
    }

    public function partial(string $template, array $data = []): string
    {
        return $this->renderFile($template, array_merge($this->shared, $data));
    }

    private function renderFile(string $template, array $data): string
    {
        $file = $this->basePath . '/' . ltrim($template, '/') . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("Sablon bulunamadi: {$template}");
        }
        $data['__view'] = $this;
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }

    // --- Section API (layout icin) ---
    public function start(string $name): void
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    public function stop(): void
    {
        $name = array_pop($this->sectionStack);
        $this->sections[$name] = ob_get_clean();
    }

    public function section(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    public function hasSection(string $name): bool
    {
        return isset($this->sections[$name]);
    }
}
