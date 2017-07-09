<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\Loader;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Yaml
{
    public function load(string $filePath): array
    {
        $redirectionsArray = SymfonyYaml::parse(file_get_contents($filePath));

        $redirections = [];

        foreach ($redirectionsArray as $origin => $destination)
        {
            $redirections[] = new Redirection(
                new Uri($origin),
                new Uri($destination)
            );
        }

        return $redirections;
    }
}
