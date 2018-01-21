<?php

namespace spec\GorkaLaucirica\RedirectChecker\Domain;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\RedirectionTraceItem;
use GorkaLaucirica\RedirectChecker\Domain\StatusCode;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedirectionSpec extends ObjectBehavior
{
    function let(Uri $origin, Uri $destination)
    {
        $this->beConstructedWith($origin, $destination);
        $this->shouldHaveType(Redirection::class);
    }

    function it_checks_a_valid_redirection(
        RedirectionTraceItem $redirectionTraceItem1,
        RedirectionTraceItem $redirectionTraceItem2,
        Uri $destination,
        Uri $uri1,
        StatusCode $statusCode
    )
    {
        $destination->__toString()->willReturn('https://www.someurl.com/page');
        $redirectionTraceItem2->uri()->willReturn($uri1);
        $redirectionTraceItem2->statusCode()->willReturn($statusCode);
        $statusCode->isSuccessful()->willReturn(true);
        $uri1->__toString()->willReturn('https://www.someurl.com/page');

        $this->isValid([$redirectionTraceItem1, $redirectionTraceItem2])->shouldReturn(true);
    }

    function it_is_not_valid_when_the_last_redirection_and_expected_destination_dont_match(
        RedirectionTraceItem $redirectionTraceItem1,
        RedirectionTraceItem $redirectionTraceItem2,
        Uri $destination,
        Uri $uri1,
        StatusCode $statusCode
    )
    {
        $redirectionTraceItem2->uri()->willReturn($uri1);
        $redirectionTraceItem2->statusCode()->willReturn($statusCode);
        $statusCode->isSuccessful()->willReturn(true);
        $uri1->__toString()->willReturn('https://www.someurl.com/page');
        $destination->__toString()->willReturn('https://www.someurl.com/other-page');

        $this->isValid([$redirectionTraceItem1, $redirectionTraceItem2])->shouldReturn(false);
    }

    function it_is_not_valid_when_the_status_code_is_not_successful(
        RedirectionTraceItem $redirectionTraceItem1,
        RedirectionTraceItem $redirectionTraceItem2,
        Uri $destination,
        Uri $uri1,
        StatusCode $statusCode
    )
    {
        $redirectionTraceItem2->uri()->willReturn($uri1);
        $redirectionTraceItem2->statusCode()->willReturn($statusCode);
        $statusCode->isSuccessful()->willReturn(false);
        $uri1->__toString()->willReturn('https://www.someurl.com/page');
        $destination->__toString()->willReturn('https://www.someurl.com/page');

        $this->isValid([$redirectionTraceItem1, $redirectionTraceItem2])->shouldReturn(false);
    }

    function it_is_not_valid_when_no_redirections_in_trace()
    {
        $this->isValid([])->shouldReturn(false);
    }
}
