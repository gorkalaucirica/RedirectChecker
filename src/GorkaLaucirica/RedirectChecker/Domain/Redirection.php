<?php

namespace GorkaLaucirica\RedirectChecker\Domain;

class Redirection
{
    private $origin;
    private $destination;

    public function __construct(Uri $origin, Uri $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function isValid(array $uriTrace) : bool
    {
        if(count($uriTrace) === 0) {
            return false;
        }

        $lastUri = $uriTrace[count($uriTrace) - 1];

        if(!$lastUri instanceof RedirectionTraceItem) {
            throw new \InvalidArgumentException('Each element of trace must be instance of RedirectionTraceItem');
        }

        return $lastUri->uri()->__toString() === $this->destination->__toString()
            && $lastUri->statusCode()->isSuccessful();
    }

    public function origin(): Uri
    {
        return $this->origin;
    }

    public function destination(): Uri
    {
        return $this->destination;
    }
}
