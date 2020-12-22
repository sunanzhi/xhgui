<?php

namespace XHGui;

use Slim\Http\Response;
use Slim\Slim as App;
use Slim\Views\Twig;

abstract class AbstractController
{
    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function render(string $template, array $data = []): void
    {
        /** @var Response $response */
        $response = $this->app->response;
        /** @var Twig $renderer */
        $renderer = $this->app->view;

        $renderer->appendData($data);
        $body = $renderer->fetch($template);
        $response->write($body);
    }

    /**
     * Redirect to the URL of a named route
     *
     * @param string $name The route name
     * @param array $params Associative array of URL parameters and replacement values
     */
    protected function redirectTo(string $name, array $params = []): void
    {
        $this->app->redirectTo($name, $params);
    }

    protected function flashSuccess(string $message): void
    {
        $this->app->flash('success', $message);
    }

    protected function config(string $key)
    {
        return $this->app->getContainer()->get($key);
    }
}
