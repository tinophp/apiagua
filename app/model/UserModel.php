<?php 


namespace App\Model;


use App\model\DrinkModel;
use PDO;

Class UserModel {

	private static $db;

	public function __construct($db)
    {
		self::$db = $db;
	}
	

	public static function getAllUser(){
		$db = self::$db;
        $data = array();
		$query = $db->prepare("SELECT * FROM usuario");
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
	}
	

	public static function saveUser($email, $name, $password){
		$retorno = self::validateUserOnSave($name, $email);
		if(!empty($retorno)){
			return "userWasInserted";
		}else{
			$token = md5(rand(5, 1500)."apiAgua");
	
			$db = self::$db;
			$query = $db->prepare("INSERT INTO usuario(token, email, name, password) VALUES (:token, :email, :name, :password)");
			$query->bindValue(":token", $token);
			$query->bindValue(":email", $email);
			$query->bindValue(":name", $name);
			$query->bindValue(":password", $password);
			$query->execute();
			return $db->lastInsertId();
		}
	}

    public static function deleteUser($user_id, $token)
    {
        $db = self::$db;
        $query = $db->prepare("SELECT * FROM usuario WHERE id = :userId AND token = :token");
        $query->bindValue(":userId", $user_id);
        $query->bindValue(":token", $token);
        $query->execute();
        $retorno = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($retorno)) {
            $queryDel = $db->prepare("DELETE FROM usuario WHERE id = :id");
            $queryDel->bindValue(":id", $user_id);
            $ret = $queryDel->execute();
            if($ret){
                return 1;
            }
        }else{
            return "userIdOrTokenError";
        }
    }

	public static function updateUser($userId, $name, $email, $password){
        $db = self::$db;
        $queryUpdate = $db->prepare("UPDATE usuario SET name = :nome, email = :emaill, password = :senha  WHERE id = :id");
        $queryUpdate->bindValue(":id", $userId);
        $queryUpdate->bindValue(":nome", $name);
        $queryUpdate->bindValue(":emaill", $email);
        $queryUpdate->bindValue(":senha", $password);
        $updateResult = $queryUpdate->execute();
        return $updateResult;
    }
    
    // validation function 
    public static function validateUserOnSave($name, $email){

		$query = self::$db->prepare("SELECT * FROM usuario WHERE name = :name and email = :email");
		$query->bindValue(":name", $name);
		$query->bindValue(":email", $email);
		$query->execute();
		$retorno = $query->fetch(PDO::FETCH_ASSOC);
		return $retorno;
    }
    

    public static function validaUserId($userId){
        $db = self::$db;
		$query = $db->prepare("SELECT * FROM usuario WHERE id = :id");
		$query->bindParam(":id", $userId);
		$query->execute();
		$usuario = $query->fetch(PDO::FETCH_ASSOC);
		if(!empty($usuario)){
            return $usuario;
		}else{
            return "userNotFound";
		}
	}
    
    public static function validaToken($token){
		$db = self::$db;
		$query = $db->prepare("SELECT * FROM usuario WHERE token = :token");
		$query->bindValue(":token", $token);
		$query->execute();
		$retorno = $query->fetch(PDO::FETCH_ASSOC);
		return $retorno;
	}

    public static function validateAllTokens($token){
		$valToken = self::validaToken($token);
		if(!empty($valToken)){
			return true;
		}else{
			return false;
		}
	}
}