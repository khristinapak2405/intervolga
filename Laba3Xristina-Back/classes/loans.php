<?php
require_once "tableModule.php";

class Collectors extends TableModule
{
	protected function getTableName(): string
	{
		return "loans";
	}
}