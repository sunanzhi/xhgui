<?php

namespace XHGui\Test;

use LazyProperty\LazyPropertiesTrait;
use MongoDB;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use XHGui\Application;
use XHGui\Controller;
use XHGui\Saver\SaverInterface;
use XHGui\Searcher\MongoSearcher;
use XHGui\Searcher\SearcherInterface;

trait LazyContainerProperties
{
    use LazyPropertiesTrait;

    /** @var Application */
    protected $di;
    /** @var Controller\ImportController */
    protected $import;
    /** @var MongoSearcher */
    protected $mongo;
    /** @var MongoDB */
    protected $mongodb;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var Controller\RunController */
    protected $runs;
    /** @var App */
    protected $app;
    /** @var array */
    protected $config;
    /** @var Environment */
    protected $env;
    /** @var SearcherInterface */
    protected $searcher;
    /** @var SaverInterface */
    protected $saver;
    /** @var TwigView */
    protected $view;
    /** @var Controller\WatchController */
    protected $watches;

    protected function setupProperties(): void
    {
        $this->initLazyProperties([
            'di',
            'app',
            'config',
            'env',
            'import',
            'mongo',
            'mongodb',
            'request',
            'response',
            'runs',
            'saver',
            'searcher',
            'view',
            'watches',
        ]);
    }

    protected function getDi()
    {
        $di = new Application();

        // Use a test databases
        // TODO: do the same for PDO. currently PDO uses DSN syntax and has too many variations
        $di['mongodb.database'] = 'test_xhgui';

        /** @var \Slim\Container $container */
        $container = $di['app']->getContainer();
        $container->register(new class() implements ServiceProviderInterface {
            public function register(Container $container): void
            {
                $container['view.class'] = TwigView::class;
            }
        });

        $di->boot();

        return $di;
    }

    protected function getApp()
    {
        return $this->di['app'];
    }

    protected function getConfig()
    {
        return $this->di['config'];
    }

    protected function getEnv()
    {
        return Environment::mock();
    }

    protected function getImport()
    {
        return $this->di[Controller\ImportController::class];
    }

    protected function getMongo()
    {
        return $this->di['searcher.mongodb'];
    }

    protected function getMongoDb()
    {
        return $this->di[MongoDB::class];
    }

    protected function getRequest(): Request
    {
        return $this->di['app']->request();
    }

    protected function getResponse(): Response
    {
        return $this->di['app']->response();
    }

    protected function getSearcher()
    {
        return $this->di['searcher'];
    }

    protected function getRuns()
    {
        return $this->di[Controller\RunController::class];
    }

    protected function getSaver()
    {
        return $this->di['saver'];
    }

    protected function getView(): Twig
    {
        return $this->di['app']->getContainer()->get('view');
    }

    protected function getWatches()
    {
        return $this->di[Controller\WatchController::class];
    }
}
