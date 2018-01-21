<?php

namespace spec\GorkaLaucirica\RedirectChecker\Application\Query;

use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionHandler;
use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionQuery;
use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\RedirectionTraceItem;
use GorkaLaucirica\RedirectChecker\Domain\RedirectTraceProvider;
use GorkaLaucirica\RedirectChecker\Domain\StatusCode;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckRedirectionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable(
        RedirectTraceProvider $redirectTraceProvider,
        CheckRedirectionQuery $query,
        RedirectionTraceItem $redirection1,
        RedirectionTraceItem $redirection2,
        Uri $uri1,
        Uri $uri2,
        StatusCode $statusCode
    ) {
        $this->beConstructedWith($redirectTraceProvider);
        $this->shouldHaveType(CheckRedirectionHandler::class);

        $query->origin()->willReturn('http://example.com');
        $query->destination()->willReturn('https://www.example.com');

        $statusCode->statusCode()->willReturn(301);
        $statusCode->isSuccessful()->willReturn(true);

        $redirection1->uri()->willReturn($uri1);
        $uri1->__toString()->willReturn('https://example.com');
        $redirection1->statusCode()->willReturn($statusCode);

        $redirection2->uri()->willReturn($uri2);
        $uri2->__toString()->willReturn('https://www.example.com');
        $redirection2->statusCode()->willReturn($statusCode);

        $redirectTraceProvider->getRedirectionTrace(Argument::type(Redirection::class))->willReturn([
            $redirection1,
            $redirection2
        ]);

        $this->__invoke($query)->shouldReturn([
            'isValid' => true,
            'trace' => [
                [
                    'uri' =>  'https://example.com',
                    'statusCode' => 301
                ],
                [
                    'uri' =>  'https://www.example.com',
                    'statusCode' => 301
                ],
            ]
        ]);
    }
}
