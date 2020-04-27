<?php 

namespace App\Controller;
use App\model\DrinkModel;

Class DrinkController {

	
	public static $drink;

	public function __construct(DrinkModel $drink)
    {
		self::$drink = $drink;
	}

	public static function inserirDrink($drink, $userId){
        $drinks = self::$drink::insertUserDrink($drink, $userId);
		return $drinks;
	}

	public static function contarDrink($userId){
		$drinks = self::$drink::countDrink($userId);
		return $drinks;
	}
	
	public static function hostoricoDrink($userId){
		$drinks = self::$drink::drinkHistory($userId);
		$spreadDrinks = [];
		$cont=0;
        foreach ($drinks as $drink) {
			$spreadDrinks[$cont]['quantidade'] = $drink['quantidade'];
			$spreadDrinks[$cont]['date'] = $drink['date'];
			$cont++;
        }
        return $spreadDrinks;
	}
    
    public static function obterRank() {
        $drinks = self::$drink::getRanking();
        return $drinks;
    }
	
}