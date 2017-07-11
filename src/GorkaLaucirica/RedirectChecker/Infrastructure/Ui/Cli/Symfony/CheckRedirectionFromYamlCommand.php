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
        $fails = 0;
        $success = 0;

        $client = new Guzzle();

        $redirections = (new Yaml())->load($input->getArgument('filepath'));

        /** @var Redirection $redirection */
        foreach ($redirections as $redirection) {
            $redirectionTrace = $client->getRedirectionTrace($redirection);

            if ($redirection->isValid($redirectionTrace)) {
                $output->writeln(
                    sprintf(
                        '<fg=green>✓</> %s -> <fg=green> %s </>',
                        $redirection->origin(),
                        $redirection->destination()
                    )
                );
                $success++;
            } else {
                $output->writeln(
                    sprintf(
                        '<fg=red>✗</> %s -> %s',
                        $redirection->origin(),
                        $redirection->destination()
                    )
                );
                $fails++;
            }

            if ($input->getOption('verbose')) {
                $output->writeln(sprintf('├── %s', $redirection->origin()));

                foreach ($redirectionTrace as $traceItem) {
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
