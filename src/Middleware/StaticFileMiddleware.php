<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class StaticFileMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private string $assetsPath;

    /**
     * @param string $assetsPath
     */
    public function __construct(string $assetsPath)
    {
        $this->assetsPath = $assetsPath;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * 
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $filePath = $this->assetsPath . $path;
        if (file_exists($filePath) && is_file($filePath)) {
            $response = new Response();
            $fileContents = file_get_contents($filePath);
            $response->getBody()->write($fileContents);
            return $response->withHeader('Content-Type', $this->getContentType($filePath));
        }

        return $handler->handle($request);
    }

    /**
     * @param string $filePath
     * 
     * @return string
     */
    private function getContentType(string $filePath): string
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'js' => 'application/javascript',
            'css' => 'text/css',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
