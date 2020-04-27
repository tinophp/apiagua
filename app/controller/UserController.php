<?php 

namespace App\Controller;
use App\model\UserModel;
use App\model\DrinkModel;

Class UserController {


	public static $user;

	public static $drink;

	public function __construct(UserModel $user, DrinkModel $drink)
    {
		self::$user = $user;
		self::$drink = $drink;
	}

	public static function obtenhaTodosUsuario(){
        $todosUsuario = self::$user::getAllUser();
        return $todosUsuario;
	}
	
	public static function salvarUsuario($email, $name, $password){
		
        $usuarioInserido = self::$user::saveUser($email, $name, $password);
        if ($usuarioInserido == "userWasInserted") {
            return "userWasInserted";
        }else{
            return $usuarioInserido;
        }
	}

	public static function deletarUsuario($user_id, $token)
    {
        $deletar = self::$user::deleteUser($user_id, $token);
        if($deletar == 1){
            return "deletadoSucesso";
        }else{
            return "userIdOrTokenError";
        }
	}

	public static function atualizarUsuario($userId, $token, $name, $email, $password){
		$retorno = self::$user::validaToken($token);
		if($retorno){
			$atualizado = self::$user::updateUser($userId, $name, $email, $password);
			if ($atualizado) {
				return $atualizado;
			}
		}else{
			return "notPossibleTokenInvalid";
		}
	}

	public static function obtenhaUmUsuario($userId, $token){
		$usuario = self::$user::validaUserId($userId);
		if($usuario != "userNotFound"){
			$drink = self::$drink::countDrink($usuario["id"]);
			$usuario["drink_counter"] = $drink;
			return $usuario;
		}else{
			return $usuario;
		}
	}


    // isso deve ir para controller e model drink
	public static function incrimentUserDrink($userId, $token, $drink_ml){
		$retorno = self::$user::validaToken($token);
		if($retorno){
			$returnUserValid = self::$user::validaUserId($userId);
			if($returnUserValid == "userNotFound"){
				return $returnUserValid;
			}else{
				$drink_counter = self::$drink::insertUserDrink($drink_ml, $userId);
				$returnUserValid["drink_counter"] = $drink_counter;
				return $returnUserValid;
			}
		}else{
			return "notPossibleTokenInvalid";
		}
	}
	
}