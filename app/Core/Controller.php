<?php

namespace App\Core;

/**
 * Tum controller'larin temel sinifi. View render ve yanit yardimcilari.
 */
abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Public/kurumsal sayfa render eder (layouts/main).
     */
    protected function render(string $template, array $data = [], ?string $layout = 'layouts/main'): never
    {
        Response::securityHeaders();
        $html = $this->view->render($template, $data, $layout);
        Response::html($html);
    }

    protected function json(array $data, int $status = 200): never
    {
        Response::json($data, $status);
    }

    protected function redirect(string $to): never
    {
        Response::redirect($to);
    }

    protected function back(string $fallback = '/'): never
    {
        Response::back($fallback);
    }

    protected function withError(string $message): void
    {
        Session::flash('error', $message);
    }

    protected function withSuccess(string $message): void
    {
        Session::flash('success', $message);
    }

    protected function abort(int $code, string $message = ''): never
    {
        Response::securityHeaders();
        $html = $this->view->render('errors/' . $code, ['message' => $message], 'layouts/error');
        Response::html($html, $code);
    }
}
