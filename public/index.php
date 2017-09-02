<?php

use Movies\Middleware\RenderMoviesMiddleware;
use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\AppFactory;
use Zend\ServiceManager\ServiceManager;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$container = new ServiceManager();
$container->setService(
    \GuzzleHttp\ClientInterface::class,
    new GuzzleHttp\Client([
        'base_uri' => 'http://nginx'
    ])
);
$container->setFactory(
    RenderMoviesMiddleware::class,
    \Movies\Middleware\RenderMoviesMiddlewareFactory::class
);

$app = AppFactory::create($container);

$movieData = require_once('data/movies.php');

$app->get('/api', function (ServerRequestInterface $request, ResponseInterface $response, $next) use($movieData) {
    return new JsonResponse($movieData);
});

$app->get('/', RenderMoviesMiddleware::class);

$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();
$app->run();
