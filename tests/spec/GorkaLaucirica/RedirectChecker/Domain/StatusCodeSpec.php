<?php

namespace spec\GorkaLaucirica\RedirectChecker\Domain;

use GorkaLaucirica\RedirectChecker\Domain\StatusCode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StatusCodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(301);
        $this->shouldHaveType(StatusCode::class);

        $this->statusCode()->shouldReturn(301);
    }
}
