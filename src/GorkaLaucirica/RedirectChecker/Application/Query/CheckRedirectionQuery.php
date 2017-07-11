<?php

namespace GorkaLaucirica\RedirectChecker\Application\Query;

class CheckRedirectionQuery
{
    private $origin;
    private $destination;

    public function __construct(string $origin, string $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function origin()
    {
        return $this->origin;
    }

    public function destination()
    {
        return $this->destination;
    }
}
