<?php

namespace spec\GorkaLaucirica\RedirectChecker\Application\Query;

use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionHandler;
use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionQuery;
use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\RedirectTraceProvider;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckRedirectionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable(
        RedirectTraceProvider $redirectTraceProvider,
        CheckRedirectionQuery $query,
        Uri $redirection1,
        Uri $redirection2
    ) {
        $this->beConstructedWith($redirectTraceProvider);
        $this->shouldHaveType(CheckRedirectionHandler::class);

        $query->origin()->willReturn('http://example.com');
        $query->destination()->willReturn('https://www.example.com');

        $redirection1->__toString()->willReturn('https://example.com');
        $redirection2->__toString()->willReturn('https://www.example.com');

        $redirectTraceProvider->getRedirectionTrace(Argument::type(Redirection::class))->willReturn([
            $redirection1,
            $redirection2
        ]);

        $this->__invoke($query)->shouldReturn([
            'isValid' => true,
            'trace' => [
                'https://example.com',
                'https://www.example.com'
            ]
        ]);
    }
}
