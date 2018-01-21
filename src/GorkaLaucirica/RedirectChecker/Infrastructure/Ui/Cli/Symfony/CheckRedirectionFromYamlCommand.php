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
        $this->setName('yaml')
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
            $output->writeln($this->renderResultLine($redirection, $origin, $destination));
            $redirection['isValid'] ? $success++ : $fails++;

            if ($input->getOption('verbose')) {
                $output->writeln(sprintf('├── %s', $origin));

                foreach ($redirection['trace'] as $traceItem) {
                    $output->writeln($this->renderTraceItemLine($traceItem));
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

    private function renderResultLine(array $redirection, string $origin, string $destination): string
    {
        $isValid = $redirection['isValid'];

        $redirectionTraceLength = count($redirection['trace']);

        $statusCode = $redirectionTraceLength === 0
            ? 404
            : $redirection['trace'][$redirectionTraceLength - 1]['statusCode'];

        return sprintf(
            '<fg=%1$s>%2$s</> [%3$d] %4$s -> <fg=%1$s> %5$s </>',
            $isValid ? 'green' : 'red',
            $isValid ? '✓' : '✗',
            $statusCode,
            $origin,
            $destination
        );
    }

    private function renderTraceItemLine(array $traceItem) : string
    {
        return sprintf('├── [%d] %s', $traceItem['statusCode'], $traceItem['uri']);
    }
}
