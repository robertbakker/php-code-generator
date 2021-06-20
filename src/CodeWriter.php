<?php


namespace RobertBakker\PhpCodeGenerator;


use Composer\Autoload\ClassLoader;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use RobertBakker\PhpCodeGenerator\Attribute\Generated;

class CodeWriter
{
    public function __construct(private ClassLoader $classLoader)
    {
    }

    public function writeNamespace(PhpNamespace $namespace): void
    {
        $prefixesPsr4 = $this->classLoader->getPrefixesPsr4();
        $srcFolder = realpath(getcwd() . "/src");
        $outputFolder = null;
        $outputNamespace = null;
        foreach ($prefixesPsr4 as $ns => $folder) {
            $path = realpath($folder[0]);
            if ($path === $srcFolder) {
                $outputFolder = $path;
                $outputNamespace = trim($ns, "\\");
            }
        }

        if (substr(trim($namespace->getName(), "\\"), 0, strlen($outputNamespace)) !== trim($outputNamespace, "\\")) {
            $msg = sprintf("Don't know where to write. Found location '%s' as 'src' directory for namespace '%s', 
            but want to write classes for namespace '%s'. ", $outputFolder, $outputNamespace, $namespace->getName());
            throw new \Exception($msg);
        }

        $classes = array_keys($namespace->getClasses());
        if (count($classes) === 0) {
            throw new \Exception(sprintf("Found 0 classes under namespace: %s", $namespace->getName()));
        }
        if (count($classes) > 1) {
            throw new \Exception(sprintf("Can only write one class at a time, found classes: %s, under namespace: %s", implode(", ", $classes), $namespace->getName()));
        }

        $className = $classes[0];
        $fullyQualifiedClassName = trim($namespace->getName() . "\\" . $className, "\\");

        if (interface_exists($fullyQualifiedClassName) || class_exists($fullyQualifiedClassName)) {
            $reflectionClass = new \ReflectionClass($fullyQualifiedClassName);
            $attributes = $reflectionClass->getAttributes(Generated::class);
            if (count($attributes) === 0) {
                throw new \Exception(sprintf("There is already an existing class/interface '%s' without a #[Generated] attribute, stopping...", $fullyQualifiedClassName));
            }
        }

        $leftOverClassName = $className;
        if (substr($fullyQualifiedClassName, 0, strlen($outputNamespace)) == $outputNamespace) {
            $leftOverClassName = trim(substr($fullyQualifiedClassName, strlen($outputNamespace)), "\\");
        }

        $path = explode("\\", $leftOverClassName);
        array_pop($path);

        $finalPath = $outputFolder . "/" . (count($path) > 0 ? implode("/", $path) . "/" : "") . $className . ".php";

        $finalDir = dirname($finalPath);
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        $printer = new Printer;
        $finalString = "<?php\n\n" . $printer->printNamespace($namespace);
        file_put_contents($finalPath, $finalString);
    }
}