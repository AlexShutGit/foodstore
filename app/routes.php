<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeController::class);

    $app->group('', fn() => 
        $app->get('/login', LoginController::class),
        $app->post('/login-post', LoginController::class . ':login'),
        $app->post('/logout-post', LoginController::class . ':logout'),
    );

    $app->group('', fn() =>  
        $app->get('/register', RegisterController::class),
        $app->post('/register-post', RegisterController::class . ':register'),
    );
};
