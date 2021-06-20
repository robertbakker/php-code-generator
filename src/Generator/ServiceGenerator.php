<?php


namespace RobertBakker\CodeGenerator\Generator;


use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ServiceGenerator
{

    public function generate(string $namespace, string $className): PhpNamespace
    {
        $namespace = new PhpNamespace($namespace);
        $class = $namespace->addClass($className);
        $constructor = $class->addMethod('__construct');

        $class->setFinal();

        // Implement the LoggerAwareInterface
        $namespace->addUse(NullLogger::class);
        $constructor->setBody('$this->logger = new NullLogger();');
        $namespace->addUse(LoggerAwareInterface::class);
        $namespace->addUse(LoggerInterface::class);
        $class->addProperty("logger")->setPrivate()->setType(LoggerInterface::class);
        $class->addImplement(LoggerAwareInterface::class);
        $setLoggerMethod = $class
            ->addMethod("setLogger")
            ->setReturnType("void")
            ->setBody('$this->logger = $logger;');
        $setLoggerMethod
            ->addParameter("logger")
            ->setType(LoggerInterface::class);


        return $namespace;
    }

}