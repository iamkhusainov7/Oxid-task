<?php

namespace App\DataProviders\Templates;

use App\DataProviders\ExceptionHandlers\ApiExceptionHandlerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;
use Laravel\Lumen\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

abstract class ApiDataProvider
{
    protected const ENDPOINT_PARAM_KEY = 'endpoint';

    public function __construct(
        protected ClientInterface $request,
        protected ApiExceptionHandlerInterface $exceptionHandler
    ) {

    }

    /**
     * @param string     $url
     * @param Collection $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws Throwable
     */
    protected function get(string $url, Collection $options): ResponseInterface
    {
        try {
            $url = $this->parseUrl($url, $options->get(self::ENDPOINT_PARAM_KEY, $options->toArray()));

            return $this->request->request(Request::METHOD_GET, $url, $options->toArray());
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() === Response::HTTP_NOT_FOUND) {
                throw new NotFoundHttpException(sprintf('Endpoint "%s" was not found!', $url));
            }

            if ($e->getResponse()->getStatusCode() >= Response::HTTP_INTERNAL_SERVER_ERROR) {
                throw new NotFoundHttpException(sprintf('Unknown resource error. Message: "%s"', $e->getMessage()));
            }

            $this->exceptionHandler->handle($e);
        }
    }

    protected function parseUrl(string $url, array $params): string
    {
        foreach ($params as $key => $value) {
            if (! preg_match("/\{$key}/", $url)) {
                continue;
            }

            $url = preg_replace("/\{$key}/", (string) $value, $url);
        }

        return $url;
    }
}
