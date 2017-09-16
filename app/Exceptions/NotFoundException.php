<?php

namespace Cemal\Exceptions;

use Exception;

/**
* exception class for not found error
*/
class NotFoundException extends Exception
{
	function __construct($entityName)
	{
		parent::__construct(sprintf("'%s' not found", $entityName));
	}
}