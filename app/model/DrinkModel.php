<?php 

namespace App\Model;

use PDO;

Class DrinkModel {

	private static $db;

	public function __construct($db)
    {
		self::$db = $db;
	}

    public static function insertUserDrink($drink, $userId){
		$db = self::$db;
		$query = $db->prepare("INSERT INTO drinks(id, quantidade, idusuario) VALUES (DEFAULT, :quantidade, :userId)");
		$query->bindValue(":quantidade", $drink);
		$query->bindValue(":userId", $userId);
		$query->execute();
		$idInserted = $db->lastInsertId();
		$drinks = self::countDrink($userId);
		return $drinks;
	}

	public static function countDrink($userId){
		$db = self::$db;
		$query2 = $db->prepare("SELECT count(quantidade) AS drink_counter FROM drinks WHERE idusuario = $userId");
		$query2->execute();
		$drinks = $query2->fetch(PDO::FETCH_ASSOC);
		return $drinks;
	}
	
	public static function drinkHistory($userId){
		$db = self::$db;
		$query2 = $db->prepare("SELECT date, quantidade FROM drinks WHERE idusuario = $userId");
		$query2->execute();
		$drinks = $query2->fetchAll();
		return $drinks;
    }
    
    public static function getRanking() {
        $db = self::$db;
        $date = date('Y-m-d');
        $query = "SELECT usuario.name name, quantidade as ml 
			FROM drinks JOIN usuario ON usuario.id = drinks.idusuario 
			where drinks.date LIKE '{$date} %' 
			and drinks.quantidade = (select max(quantidade) from drinks)";
        $drink = $db->prepare($query);
        $drink->execute();
        return $drink->fetchAll();
    }
    
}