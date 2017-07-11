<?php

namespace spec\GorkaLaucirica\RedirectChecker\Domain;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedirectionSpec extends ObjectBehavior
{
    function let(Uri $origin, Uri $destination) {
        $this->beConstructedWith($origin, $destination);
        $this->shouldHaveType(Redirection::class);
    }

    function it_checks_a_valid_redirection(Uri $destination, Uri $redirection1, Uri $redirection2)
    {
        $destination->__toString()->willReturn('https://www.someurl.com/page');
        $redirection2->__toString()->willReturn('https://www.someurl.com/page');

        $this->isValid([$redirection1, $redirection2])->shouldReturn(true);
    }

    function it_checks_an_invalid_redirection(Uri $destination, Uri $redirection1, Uri $redirection2)
    {
        $destination->__toString()->willReturn('https://www.someurl.com/page');
        $redirection2->__toString()->willReturn('https://www.someurl.com/other-page');

        $this->isValid([$redirection1, $redirection2])->shouldReturn(false);
    }

    function it_is_not_valid_when_no_redirections_in_trace()
    {
        $this->isValid([])->shouldReturn(false);
    }
}
