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

    public function checkIsValid(array $uriTrace)
    {
        $this->uriTrace = $uriTrace;

        if(count($uriTrace) === 0) {
            return false;
        }

        $lastUri = $uriTrace[count($uriTrace) - 1];

        if(!$lastUri instanceof Uri) {
            throw new \InvalidArgumentException('Each element of uriTrace must be instance of Uri');
        }

        return $lastUri->__toString() === $this->destination->__toString();
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
