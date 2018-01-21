<?php

namespace GorkaLaucirica\RedirectChecker\Domain;

class StatusCode
{
    private $statusCode;

    public function __construct(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function statusCode() : int
    {
        return $this->statusCode;
    }

    public function isSuccessful() : bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 399;
    }
}
