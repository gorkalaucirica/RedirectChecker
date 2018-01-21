<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\RedirectTraceProvider;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\RedirectionTraceItem;
use GorkaLaucirica\RedirectChecker\Domain\RedirectTraceProvider;
use GorkaLaucirica\RedirectChecker\Domain\RequestException;
use GorkaLaucirica\RedirectChecker\Domain\StatusCode;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RedirectMiddleware;

final class Guzzle implements RedirectTraceProvider
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['allow_redirects' => ['track_redirects' => true]]);
    }

    public function getRedirectionTrace(Redirection $redirection): array
    {
        $request = new Request('GET', $redirection->origin());

        try {
            $response = $this->client->send($request);

            return $this->generateRedirectionTrace($response);

        } catch (ClientException $e) {
            $redirectionTrace = $this->generateRedirectionTrace($e->getResponse());
            return $this->replaceLastItemsStatusCode($redirectionTrace, $e->getResponse()->getStatusCode());
        }
    }

    public function generateRedirectionTrace(Response $response) : array
    {
        $statusHistory = $response->getHeader(RedirectMiddleware::STATUS_HISTORY_HEADER);
        $urlHistory = $response->getHeader(RedirectMiddleware::HISTORY_HEADER);

        if(count($statusHistory) != count($urlHistory)) {
            return [];
        }

        $redirectionTrace = [];

        for($i = 0; $i < count($statusHistory); $i++)
        {
            $redirectionTrace[] = new RedirectionTraceItem(
                new Uri($urlHistory[$i]),
                new StatusCode($statusHistory[$i])
            );
        }

        return $redirectionTrace;
    }

    private function replaceLastItemsStatusCode(array $redirectionTrace, int $statusCode) : array
    {
        $redirectionTrace[count($redirectionTrace) -1] = new RedirectionTraceItem(
            $redirectionTrace[count($redirectionTrace) -1]->uri(),
            new StatusCode($statusCode)
        );

        return $redirectionTrace;
    }
}
