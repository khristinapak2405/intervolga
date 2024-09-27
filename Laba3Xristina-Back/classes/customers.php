<?php
require_once "tableModule.php";

class Crews extends TableModule
{
	protected function getTableName(): string
	{
		return "customers";
	}
}