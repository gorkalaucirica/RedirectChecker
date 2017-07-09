<?php

namespace GorkaLaucirica\RedirectChecker\Domain;

interface RedirectTraceProvider
{
    /**
     * @return array of Uri's
     */
    public function getRedirectionTrace(Redirection $redirection): array;
}
