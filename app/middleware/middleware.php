<?php

use Slim\App;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();

    $app->add(TwigMiddleware::class);

    $app->addRoutingMiddleware();

    return $app;
};
