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
    $rank = $drinkController::obterRank();
    if (!empty($rank)) {
        echo json_encode($rank);die;
    }else{
        echo json_encode(["erro" => "Alert, drinks não contablizados ainda"]); die;
    }
}else{
    echo json_encode(["erro" => "Erro, Method ".$method." não permetido"]); die;
}