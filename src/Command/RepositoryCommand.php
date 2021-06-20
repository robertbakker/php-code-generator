<?php


namespace RobertBakker\PhpCodeGenerator\Command;


use RobertBakker\PhpCodeGenerator\CodeWriter;
use RobertBakker\PhpCodeGenerator\Generator\RepositoryGenerator;
use RobertBakker\PhpCodeGenerator\Generator\ServiceGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class RepositoryCommand extends Command
{
    protected static $defaultName = 'ddd:repository';

    public function __construct(private CodeWriter $codeWriter, private string $defaultNamespace)
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setDescription("Generate a repository interface for a Domain Driven Design (DDD) style project");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $generator = new RepositoryGenerator();
        $namespace = $this->defaultNamespace;

        $question = new Question(sprintf("For which entity is the repository? (e.g. User will result in '%s')",
            trim($namespace, "\\") . "\\Repository\\UserRepositoryInterface"));
        do {
            $entityName = $io->askQuestion($question);
        } while (empty($entityName));

        $namespaceService = preg_replace('/Repository$/', '', $namespace);
        $namespaceService = trim($namespaceService, "\\") . "\\Repository";

        $generated = $generator->generate($namespaceService, $entityName);
        $this->codeWriter->writeNamespace($generated);

        return Command::SUCCESS;
    }
}