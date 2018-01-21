<?php

namespace GorkaLaucirica\RedirectChecker\Application\Query;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\RedirectionTraceItem;
use GorkaLaucirica\RedirectChecker\Domain\RedirectTraceProvider;
use GorkaLaucirica\RedirectChecker\Domain\RequestException;
use GorkaLaucirica\RedirectChecker\Domain\Uri;

class CheckRedirectionHandler
{
    private $redirectTraceProvider;

    public function __construct(RedirectTraceProvider $redirectTraceProvider)
    {
        $this->redirectTraceProvider = $redirectTraceProvider;
    }

    public function __invoke(CheckRedirectionQuery $query)
    {
        $redirection = new Redirection(
            new Uri($query->origin()),
            new Uri($query->destination())
        );

        $redirectionTrace = $this->redirectTraceProvider->getRedirectionTrace($redirection);

        return [
            'isValid' => $redirection->isValid($redirectionTrace),
            'trace' => array_map(function (RedirectionTraceItem $redirectionTraceItem) {
                return [
                    'uri' => $redirectionTraceItem->uri()->__toString(),
                    'statusCode' => $redirectionTraceItem->statusCode()->statusCode()
                ];
            }, $redirectionTrace)
        ];
    }
}
