<?php

namespace spec\GorkaLaucirica\RedirectChecker\Domain;

use GorkaLaucirica\RedirectChecker\Domain\RedirectionTraceItem;
use GorkaLaucirica\RedirectChecker\Domain\StatusCode;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedirectionTraceItemSpec extends ObjectBehavior
{
    function it_is_initializable(Uri $uri, StatusCode $statusCode)
    {
        $this->beConstructedWith($uri, $statusCode);
        $this->shouldHaveType(RedirectionTraceItem::class);

        $this->uri()->shouldReturn($uri);
        $this->statusCode()->shouldReturn($statusCode);
    }
}
