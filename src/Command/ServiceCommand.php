<?php


namespace RobertBakker\PhpCodeGenerator\Command;


use RobertBakker\PhpCodeGenerator\CodeWriter;
use RobertBakker\PhpCodeGenerator\Generator\ServiceGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServiceCommand extends Command
{
    protected static $defaultName = 'ddd:service';

    public function __construct(private CodeWriter $codeWriter, private string $defaultNamespace)
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setDescription("Generate a service class for a Domain Driven Design (DDD) style project");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $generator = new ServiceGenerator();
        $namespace = $this->defaultNamespace;

        $question = new Question(sprintf("What must be the name of the service class? (e.g. UserService will result in '%s')",
            trim($namespace, "\\") . "\\UserService"));
        do {
            $className = $io->askQuestion($question);
        } while (empty($className));

        $className = preg_replace('/Service$/', '', $className);
        $className .= "Service";
        $className = ucfirst($className);

        $namespaceService = preg_replace('/Service$/', '', $namespace);
        $namespaceService = trim($namespaceService, "\\") . "\\Service";

        $generated = $generator->generate($namespaceService, $className);
        $this->codeWriter->writeNamespace($generated);

        return Command::SUCCESS;
    }
}