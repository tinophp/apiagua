<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require '../vendor/autoload.php';
require '../etc/functions.php';

use App\model\LoginModel;
use App\model\DrinkModel;
use App\config\Connection;
use App\controller\LoginController;

$method = $_SERVER['REQUEST_METHOD'];
$body = file_get_contents('php://input');
$headers = getallheaders();
$token = isset($headers["token"]) ? $headers["token"]: "" ;

$db = new Connection();
$loginModel = new LoginModel($db->openConnection());
$drinkModel = new DrinkModel($db->openConnection());
$loginController = new LoginController($loginModel, $drinkModel);

if($method == "POST" && !empty($body)){
    ///#logar com usuario
    $body = json_decode($body, true);
    $email = $body["email"];
    $password = $body["password"];

    $retorno = $loginController::logar($email, $password);

    if($retorno != "notLogged"){
        $_SESSION["username"]=$retorno["name"];
        $_SESSION["userid"]=$retorno["id"];
        $_SESSION["email"]=$retorno["email"];
        $_SESSION["token"]=$retorno["token"];
        $_SESSION["drink_counter"]=$retorno["drink_counter"]; 

        echo json_encode($retorno);
        
    }else{
        if(isset($_SESSION)){
            session_destroy();
        }
        $resposta = [
            "status" => "error",
            "message" => "Erro, Usuário não existe ou senha invalida!"
        ];
        echo json_encode($resposta);
    }
}