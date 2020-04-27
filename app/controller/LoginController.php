<?php
namespace App\Controller;
use App\model\LoginModel;
use App\model\DrinkModel;

Class LoginController {

    public static $login;

    public static $drink;

	public function __construct(LoginModel $login, DrinkModel $drink)
    {
        self::$login = $login;
        self::$drink = $drink;
	}

	public static function logar($email, $password){
        $dadosLogin = self::$login::validateUserFromLogin($email, $password);
        if(!empty($dadosLogin)) {
			$dadosLogin["drink_counter"] = self::$drink::countDrink($dadosLogin["id"]);
			return $dadosLogin;
		}else{
			return "notLogged";
		}
    }
	
}