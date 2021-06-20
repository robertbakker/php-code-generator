<?php


namespace RobertBakker\PhpCodeGenerator\Detector;


use Composer\Autoload\ClassLoader;
use Exception;

class NamespaceDetector
{

    public function __construct(private ClassLoader $classLoader)
    {
    }

    public function detect(): string
    {
        $fromEnv = $_ENV["CODE_GENERATOR_NAMESPACE"];
        if (!empty($fromEnv)) {
            return $fromEnv;
        }

        $prefixesPsr4 = $this->classLoader->getPrefixesPsr4();
        $srcFolder = realpath(getcwd() . "/src");
        foreach ($prefixesPsr4 as $namespace => $folder) {
            $path = realpath($folder[0]);
            if ($path === $srcFolder) {
                return $namespace;
            }
        }
        throw new Exception("Could not detect namespace");
    }
}