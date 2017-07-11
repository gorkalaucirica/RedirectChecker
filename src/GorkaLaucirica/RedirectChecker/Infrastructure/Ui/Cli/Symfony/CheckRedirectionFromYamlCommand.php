<?php

namespace GorkaLaucirica\RedirectChecker\Infrastructure\Ui\Cli\Symfony;

use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionHandler;
use GorkaLaucirica\RedirectChecker\Application\Query\CheckRedirectionQuery;
use GorkaLaucirica\RedirectChecker\Infrastructure\RedirectTraceProvider\Guzzle;
use GorkaLaucirica\RedirectChecker\Infrastructure\Loader\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckRedirectionFromYamlCommand extends Command
{
    protected function configure()
    {
        $this->setName('redirect-checker:yaml')
            ->addArgument('filepath', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $checkRedirectionHandler = new CheckRedirectionHandler(new Guzzle());

        $fails = 0;
        $success = 0;

        $redirections = (new Yaml())->load($input->getArgument('filepath'));

        foreach ($redirections as $origin => $destination) {
            $redirection = $checkRedirectionHandler->__invoke(new CheckRedirectionQuery($origin, $destination));
            if ($redirection['isValid']) {
                $output->writeln(
                    sprintf(
                        '<fg=green>✓</> %s -> <fg=green> %s </>',
                        $origin,
                        $destination
                    )
                );
                $success++;
            } else {
                $output->writeln(
                    sprintf(
                        '<fg=red>✗</> %s -> %s',
                        $origin,
                        $destination
                    )
                );
                $fails++;
            }

            if ($input->getOption('verbose')) {
                $output->writeln(sprintf('├── %s', $redirection->origin()));

                foreach ($redirection['trace'] as $traceItem) {
                    $output->writeln(sprintf('├── %s', $traceItem));
                }
            }
        }

        $output->writeln('');
        $output->writeln(
            sprintf(
                '%s tests run, <fg=green>%s success</>, <fg=red>%s failed</>',
                count($redirections),
                $success,
                $fails
            )
        );

        return $fails > 0 ? -1 : 0;
    }
}
