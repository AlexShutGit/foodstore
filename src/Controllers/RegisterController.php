<?php

namespace App\Controllers;

use App\Classes\Authorization;
use App\Exceptions\AuthorizationException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;


class RegisterController
{

    /**
     * @var Authorization
     */
    private Authorization $authorization;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param Authorization $authorization
     * @param ContainerInterface $container
     */
    public function __construct(Authorization $authorization, ContainerInterface $container) {
        $this->authorization = $authorization;
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
        return $this->container->get(Twig::class)->render(
            $response,
            str_replace('controller', '', strtolower($className)) . '.twig',
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * 
     * @return ResponseInterface
     */
    public function register(Request $request, Response $response): ResponseInterface
    {
        $params = (array) $request->getParsedBody();

    try {
        $this->authorization->register($params);
    } catch (AuthorizationException $exception) {
        $response->getBody()->write(json_encode([
            'error' => $exception->getMessage(),
            'form' => $params,
        ]));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(400);
    }

    return $response->withHeader('Location', '/login')
    ->withStatus(302);
    }
}

