<?php

namespace RobertBakker\PhpCodeGenerator\Repository;

interface UserRepositoryInterface
{
	function save(\User $User): void;


	function findById(): ?\User;
}
