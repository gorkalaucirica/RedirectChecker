<?php

namespace spec\GorkaLaucirica\RedirectChecker\Application\Query;

use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionQuery;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckRedirectionQuerySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('http://example.com', 'https://www.example.com');

        $this->origin()->shouldReturn('http://example.com');
        $this->destination()->shouldReturn('https://www.example.com');

        $this->shouldHaveType(CheckRedirectionQuery::class);
    }
}
