<?php

namespace RobertBakker\CodeGenerator\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Service implements LoggerAwareInterface
{
	private LoggerInterface $logger;


	public function __construct()
	{
		$this->logger = new NullLogger();
	}


	public function setLogger(LoggerInterface $logger): void
	{
		$this->logger = $logger;
	}
}
