<?php

namespace Codeages\Biz\Org\Command;

use Codeages\Biz\Framework\Context\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class TableCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('org:table')
            ->setDescription('Create a migration for the org database table')
            ->addArgument('directory', InputArgument::REQUIRED, 'Migration base directory.', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');

        $migrations = array(
            'org',
        );

        foreach ($migrations as $migration) {
            $this->copyNextMigration($directory, $migration);
        }

        $output->writeln('<info>Migration created successfully!</info>');
    }

    protected function copyNextMigration($directory, $next)
    {
        if (!$this->existMigration($directory, $next)) {
            $this->generateMigration($directory, 'biz_'.$next, __DIR__."/stub/{$next}.migration.stub");
        }
    }
}
