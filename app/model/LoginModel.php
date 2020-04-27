<?php 

namespace App\Model;

use PDO;

Class LoginModel {

    private static $db;

	public function __construct($db)
    {
		self::$db = $db;
	}

    public static function validateUserFromLogin($email, $password){
		$db = self::$db;
		$query = $db->prepare("SELECT * FROM usuario WHERE email = :email AND password = :password");
		$query->bindValue(":email", $email);
		$query->bindValue(":password", $password);
		$query->execute();
		$dados = $query->fetch(PDO::FETCH_ASSOC);
		return $dados;
    }
}