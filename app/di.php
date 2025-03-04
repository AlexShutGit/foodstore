<?php

use App\Classes\Database;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

use function DI\autowire;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = parse_ini_file('../.env');

return [
    App::class => static function(ContainerInterface $container) {
        return AppFactory::create(null, $container);
    },

    Twig::class => static function(ContainerInterface $container) {
        return Twig::create(__DIR__ . '/../templates', [
            'cache' => false,
        ]);
    },

    TwigMiddleware::class => static function(App $app) {
        return TwigMiddleware::createFromContainer($app, Twig::class);
    },

    Database::class => autowire()
    ->constructorParameter('dsn', $dotenv['DB_DSN'])
    ->constructorParameter('username', $dotenv['DB_USER'])
    ->constructorParameter('password', $dotenv['DB_USER_PASSWORD']),
];

