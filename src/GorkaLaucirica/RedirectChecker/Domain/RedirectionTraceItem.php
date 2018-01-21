<?php

namespace GorkaLaucirica\RedirectChecker\Domain;

class RedirectionTraceItem
{
    private $uri;
    private $statusCode;

    public function __construct(Uri $uri, StatusCode $statusCode)
    {
        $this->uri = $uri;
        $this->statusCode = $statusCode;
    }
    
    public function uri() : Uri
    {
        return $this->uri;
    }
    
    public function statusCode() : StatusCode
    {
        return $this->statusCode;
    }
}


