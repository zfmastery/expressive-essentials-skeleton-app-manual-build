<?php

namespace Movies\Middleware;

use GuzzleHttp\ClientInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class RenderMoviesMiddleware
 * @package Movies\Middleware
 */
class RenderMoviesMiddleware
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        $res = $this->client->request('GET', 'api');
        $renderer = (new \Movies\BasicRenderer())(
            json_decode($res->getBody())
        );
        return new HtmlResponse($renderer);
    }
}
