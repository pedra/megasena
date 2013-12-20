<?php //PDO Conect

class Connect {

	static $conn = null;
	static $dsn = 'mysql:host=localhost';

	final public static function hdl($dsn = null, $user = 'megasena', $password = 'mega!1@2#3'){
		if (static::$conn == null) {
			if($dsn == null) $dsn = static::$dsn;
			static::$conn = new PDO($dsn, $user, $password);
		}
		return static::$conn;
	}
}