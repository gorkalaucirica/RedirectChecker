<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\Ui\Cli\Symfony;

use GorkaLaucirica\RedirectChecker\Domain\Redirection;
use GorkaLaucirica\RedirectChecker\Infrastructure\RedirectTraceProvider\Guzzle;
use GorkaLaucirica\RedirectChecker\Infrastructure\Loader\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckRedirectionFromYamlCommand extends Command
{
    protected function configure()
    {
        $this->setName('redirect-checker:yaml')
            ->addArgument('filepath', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Guzzle();

        $redirections = (new Yaml())->load($input->getArgument('filepath'));

        /** @var Redirection $redirection */
        foreach ($redirections as $redirection) {
            $redirectionTrace = $client->getRedirectionTrace($redirection);

            if ($redirection->checkIsValid($redirectionTrace)) {
                $output->writeln(
                    sprintf(
                        '<fg=green>✓</> %s -> %s',
                        $redirection->origin(),
                        $redirection->destination()
                    )
                );
            } else {
                $output->writeln(
                    sprintf(
                        '<fg=red>✗</> %s -> %s',
                        $redirection->origin(),
                        $redirection->destination()
                    )
                );
            }

            if ($input->getOption('verbose')) {
                $output->writeln(sprintf('├── %s', $redirection->origin()));

                foreach ($redirectionTrace as $traceItem) {
                    $output->writeln(sprintf('├── %s', $traceItem));
                }
            }
        }
    }
}
