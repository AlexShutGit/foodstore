<?php

namespace App\Controllers;

use App\Classes\Authorization;
use App\Exceptions\AuthorizationException;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class LoginController
{
    const COOKIE_TIME = 3600;

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
    public function __invoke(Request $request, Response $response): ResponseInterface {
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
    public function login(Request $request, Response $response): ResponseInterface
    {
        $params = (array) $request->getParsedBody();

        try {
            $username = $this->authorization->login($params);
        } catch (AuthorizationException $exception) {
            $response->getBody()->write(json_encode(['error' => $exception->getMessage(),]));
            return $response->withHeader('Content-type', 'application/json')
                ->withStatus(400);
        }

        if (!empty($username)) {
            $response = FigResponseCookies::set($response, SetCookie::create('username')
                ->withValue($username)
                ->withExpires(gmdate('D, d M Y H:i:s T', time() + self::COOKIE_TIME))
            );
            return $response->withStatus(200);
        }

        return $response->withHeader('Location', '/')
        ->withStatus(302);
    }

    /**
     * @param Request $request
     * @param Response $response
     * 
     * @return ResponseInterface
     */
    public function logout(Request $request, Response $response): ResponseInterface
    {
        $response = FigResponseCookies::set($response, setCookie::create('username')
            ->withValue('')
            ->withExpires('Thu, 01 Jan 1970 00:00:01 GMT')
        );
        return $response;
    }
}

