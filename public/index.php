<?php

use App\Middleware\StaticFileMiddleware;
use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('../app/di.php');
$container = $builder->build();

AppFactory::setContainer($container);

$app = $container->get(App::class);

$routes = require __DIR__ . '/../app/routes.php';
$routes($app);
$middleware = require __DIR__ . '/../app/middleware/middleware.php';
$middleware($app);

$app->add(new StaticFileMiddleware(__DIR__ . '/../assets'));

$app->run();