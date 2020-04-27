<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
require '../etc/functions.php';

use App\model\DrinkModel;
use App\config\Connection;
use App\controller\DrinkController;

$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();

$db = new Connection();
$drinkModel = new DrinkModel($db->openConnection());
$drinkController = new DrinkController($drinkModel);

if($method == "GET"){
    
    $url = parsingUrl($_SERVER['REQUEST_URI']);
    if (isset($url[3]) && isset($url[2]) && $url[2] == 'drinkHistory') {
        $userId = $url[3];
        $spreadDrinks = $drinkController::hostoricoDrink($userId);
        if (!empty($spreadDrinks)) {
            echo json_encode($spreadDrinks);die;
        }else{
            echo json_encode(["erro" => "Erro, Não foi possivel retornar historico de Drinks"]); die;
        }
    }
}else{
    echo json_encode(["erro" => "Erro, Method ".$method." não permetido"]); die;
}
