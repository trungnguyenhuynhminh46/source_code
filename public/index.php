<?php

declare(strict_types = 1);

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

$app       = require __DIR__ . '/../bootstrap.php';
$container = $app->getContainer();
$router    = require CONFIG_PATH . '/routes.php';

$router($app);

$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

$app->add(function (Request $request, RequestHandler $handler) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    
    if ($path != '/' && substr($path, -1) == '/') {
        // recursively remove slashes when its more than 1 slash
        $path = rtrim($path, '/');

        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath($path);
        
        if ($request->getMethod() == 'GET') {
            $response = new Response();
            return $response
                ->withHeader('Location', (string) $uri)
                ->withStatus(301);
        } else {
            $request = $request->withUri($uri);
        }
    }

    return $handler->handle($request);
});

$app->run();
