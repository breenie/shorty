<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\ProcessBuilder;

$console = new Application('Shorty console', '0.0.0');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('server:run')
    ->setDefinition(array(
            new InputArgument('address', InputArgument::OPTIONAL, 'Address:port', 'localhost:8000'),
            new InputOption('docroot', 'd', InputOption::VALUE_REQUIRED, 'Document root', 'web/'),
    ))
    ->setHelp(
        <<<EOF
The <info>%command.name%</info> runs PHP built-in web server:

  <info>%command.full_name%</info>

To change default bind address and port use the <info>address</info> argument:

  <info>%command.full_name% 127.0.0.1:8080</info>

To change default docroot directory use the <info>--docroot</info> option:

  <info>%command.full_name% --docroot=htdocs/</info>

See also: http://www.php.net/manual/en/features.commandline.webserver.php
EOF

    )
    ->setDescription('Runs PHP built-in web server')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
            $documentRoot = $input->getOption('docroot');

            if (!is_dir($documentRoot)) {
                $output->writeln(
                    sprintf('<error>The given document root directory "%s" does not exist</error>', $documentRoot)
                );

                return 1;
            }

            $env = $input->getOption('env');

            if ('prod' === $env) {
                $output->writeln('<error>Running PHP built-in server in production environment is NOT recommended!</error>');
            }

            $output->writeln(sprintf("Server running on <info>http://%s</info>\n", $input->getArgument('address')));
            $output->writeln('Quit the server with CONTROL-C.');

            $builder = new ProcessBuilder(
                array(
                    PHP_BINARY,
                    '-S',
                    $input->getArgument('address'),
                    '-t',
                    $documentRoot,
                    implode(
                        DIRECTORY_SEPARATOR,
                        array_map(
                            'rtrim',
                            array($documentRoot, ('prod' === $env ? 'index.php' : 'index_dev.php'))
                        )
                    )
                )
            );

            $builder->setWorkingDirectory(getcwd() . $documentRoot);
            $builder->setTimeout(null);
            $process = $builder->getProcess();
            $process->run(
                function ($type, $buffer) use ($output) {
                    if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                        $output->write($buffer);
                    }
                }
            );

            if (!$process->isSuccessful()) {
                $output->writeln('<error>Built-in server terminated unexpectedly</error>');

                if (OutputInterface::VERBOSITY_VERBOSE > $output->getVerbosity()) {
                    $output->writeln('<error>Run the command again with -v option for more details</error>');
                }
            }

            return $process->getExitCode();
        })
;

$app->register(
    new \Kurl\Silex\Provider\DoctrineMigrationsProvider($console),
    array(
        'migrations.namespace'  => $app['migrations.namespace'],
        'migrations.directory'  => $app['migrations.directory'],
        'migrations.name'       => $app['migrations.name'],
        'migrations.table_name' => $app['migrations.table_name'],
    )
);

return $console;
