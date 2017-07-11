<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\RedirectTraceProvider;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\RedirectTraceProvider;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
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

        $response = $this->client->send($request);

        return array_map(function ($element) {
            return new Uri($element);
        }, $response->getHeader(RedirectMiddleware::HISTORY_HEADER));
    }
}
