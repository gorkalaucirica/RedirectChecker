<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\Loader;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Domain\Uri;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

final class Yaml
{
    public function load(string $filePath): array
    {
        $content = @file_get_contents($filePath);

        if(!$content) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not load the contents of the file "%s"',
                    $filePath
                )
            );
        }

        $redirectionsArray = SymfonyYaml::parse($content);

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
