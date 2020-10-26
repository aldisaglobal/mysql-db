<?php

namespace Aldisa\MySQL;

class Factory
{	
	/**
	 * Factory method that returns a Query browser with a MySQLi connection
	 *
	 * @param  mixed $config
	 * @return void
	 */
	public static function create(array $config = [])
	{
		$params = array(
			'MYSQL_HOST' => "",
			'MYSQL_USER' => "",
			'MYSQL_PASS' => "",
			'MYSQL_DB' => ""
		);

		$params = array_intersect_key(array_merge(getenv(), get_defined_constants(), $config), $params);
		if (count($params) < 4) throw new \Exception("DB Error: Missing Params");

		$conn = @new \mysqli($params['MYSQL_HOST'], $params['MYSQL_USER'], $params['MYSQL_PASS'], $params['MYSQL_DB']);
		if ($conn->connect_errno > 0) throw new \Exception("DB Error: ({$conn->connect_errno}) {$conn->connect_error}");

		return new Browser($conn);
	}
}
