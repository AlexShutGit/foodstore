<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    /**
     * @param Request $request
     * @param Response $response
     * 
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $classNameArray = explode('\\', __CLASS__);
        $className = $classNameArray[count($classNameArray) - 1];

        $cookies = $request->getCookieParams();

        $cookieValue = $cookies['username'] ?? null;

        if (!$cookieValue) {
            $response = $response->withHeader('Location', '/login')
                ->withStatus(302);
            return $response;
        }

        return $this->container->get(Twig::class)->render(
            $response,
            str_replace('controller', '', strtolower($className)) . '.twig', [
                'username' => $cookieValue,
            ]
        );
    }
}

