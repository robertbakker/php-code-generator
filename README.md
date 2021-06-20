# PHP Code generator for PHP 8+

Generate often used code by answering simple questions on the command line.

This is very early work, do not use.

## Instruction

Make a `.env.local` file containing the value:

```
CODE_GENERATOR_NAMESPACE=Your\Name\Space
```

## Usage

```
Usage:
  ./vendor/pcg [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help         Display help for a command
  list         List commands
 ddd
  ddd:service  Generate a service class for a Domain Driven Design (DDD) style project
```