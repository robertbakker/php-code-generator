<?php


namespace RobertBakker\PhpCodeGenerator\Generator;


use Nette\PhpGenerator\PhpNamespace;
use RobertBakker\PhpCodeGenerator\Attribute\Generated;

class RepositoryGenerator
{

    public function generate(string $namespace, string $entity): PhpNamespace
    {
        $namespace = new PhpNamespace($namespace);
        $interface = $namespace->addInterface($entity . "RepositoryInterface");

        $namespace->addUse(Generated::class);
        $interface->addAttribute(Generated::class);

        $saveMethod = $interface->addMethod("save");
        $saveMethod->setReturnType("void");
        $saveMethod
            ->addParameter($entity)
            ->setType($entity);

        $findByIdMethod = $interface->addMethod("findById");
        $findByIdMethod->setReturnType($entity)->setReturnNullable(true);

        return $namespace;
    }

}