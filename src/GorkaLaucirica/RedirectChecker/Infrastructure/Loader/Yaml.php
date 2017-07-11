<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\Loader;

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

        return SymfonyYaml::parse($content);
    }
}
