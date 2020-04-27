<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require '../vendor/autoload.php';
require '../etc/functions.php';

use App\model\UserModel;
use App\model\DrinkModel;
use App\config\Connection;
use App\controller\UserController;

$method = $_SERVER['REQUEST_METHOD'];
$body = file_get_contents('php://input');
$headers = getallheaders();
$token = isset($headers["token"]) ? $headers["token"]: "" ;

$db = new Connection();
$usuModel = new UserModel($db->openConnection());
$drinkModel = new DrinkModel($db->openConnection());
$usuController = new UserController($usuModel, $drinkModel);

if ($method === "POST") {
    
    $url = parsingUrl($_SERVER['REQUEST_URI']);
    
    if(!empty($body) && empty($token) ){
        //#cadastrar usuario
        $body = json_decode($body, true);
        $name = $body["nome"];
        $email = $body["email"];
        $password = $body["password"];
    
        if(!empty($name) && !empty($email) && !empty($password) ){
            $retorno = $usuController::salvarUsuario($email, $name, $password);
            if(is_numeric($retorno)){
                echo json_encode(["sucesso" => "Usuario cadastrado com sucesso"]); die;
            }elseif($retorno == "userWasInserted"){
                echo json_encode(["erro" => "Erro, esse usuario ja foi cadastrado"]); die;
            }
        }else{
            echo json_encode(["erro" => "Erro, informação em falta para cadastro."]); die;
        }
    }else{
        //#incrementar drink em ml
        if (isset($url[3]) && isset($url[4]) && $url[4] == 'drink' && !empty($token) && !empty($body)) {
            $body = json_decode($body, true);
            $idUser = $url[3];
            $drink_ml = $body["drink_ml"];
           
            $user = $usuController::incrimentUserDrink($idUser, $token, $drink_ml);
            if ($user == "userNotFound") {
                echo json_encode(["erro" => "Erro, Usuario não encontrado."]); die;
            }
            if (!empty($user["drink_counter"])) {
                echo json_encode($user);
            }
            else {
                http_response_code(404);
                echo json_encode(['error' => 'Erro ao incrementar drink ou token Inválido']); die;
            }
        }
    }
}

if($method == "GET" && !empty($token)){
    
    $url = parsingUrl($_SERVER['REQUEST_URI']);
    if (isset($url[3])) {
        //#obter um usuario pelo id
        $userId = $url[3];
        $retToken = $usuModel::validateAllTokens($token);
        if($retToken){
            $umUsuario = $usuController::obtenhaUmUsuario($userId, $token);
            if($umUsuario == "userNotFound"){
                echo json_encode(["erro" => "Usuario não encontrado"]); die;
            }else{
                unset($umUsuario["token"]);
                echo json_encode($umUsuario);
            }
        }else{
            echo json_encode(["erro" => "token inválido"]); die;
        }
    }else{
        //#obter todos usuario
        $retToken = $usuModel::validateAllTokens($token);
        if($retToken){
            $todosUsuarios = $usuController::obtenhaTodosUsuario();
            $newArr = [];
            foreach($todosUsuarios as $key => $tdosUsuario){
                unset($tdosUsuario['token']);
                $newArr[$key] = $tdosUsuario;
            }
            echo json_encode($newArr);
        }else{
            echo json_encode(["erro" => "token inválido"]);
        }
    }
}

elseif($method == "PUT" && !empty($token) && !empty($body)){
    //#atualizar usuario
    $url = parsingUrl($_SERVER['REQUEST_URI']);
    $userId = $url[3];
    $body = json_decode($body, true);
    $name = $body["name"];
    $email = $body["email"];
    $password = $body["password"];
    
    if( isset($_SESSION["userid"]) && $_SESSION["userid"] == $userId ) {
        $updateUser = $usuController::atualizarUsuario($userId, $token, $name, $email, $password);
        if($updateUser==1){
            echo json_encode(["sucesso" => "Usuario atualizado com sucesso."]); die;
        }elseif($updateUser == "notPossibleTokenInvalid"){
            echo json_encode(["erro" => "Id do usuario Invalido ou token invalido."]); die;
        }
    }else{
        //print_r($_SESSION);die;
        echo json_encode(["erro" => "Necessario autenticar para prosseguir com a operacao."]);
    }
}

elseif($method == "DELETE" && !empty($token)){
    //#deletar usuario
    $url = parsingUrl($_SERVER['REQUEST_URI']);
    $userId = isset($url[3]) ? $url[3] : "";

    if( isset($_SESSION) && $_SESSION["userid"] == $userId ) {
        $retorno = $usuController::deletarUsuario($userId, $token);
        if($retorno == "deletadoSucesso"){
            echo json_encode(["sucesso" => "Usuario deletado com sucesso"]); die;
        }elseif($retorno == "userIdOrTokenError"){
            echo json_encode(["erro" => "Id do usuario Invalido ou token errado."]); die;
        }
    }else{
        echo json_encode(["erro" => "Necessario autenticar para prosseguir com a operacao."]);
    }
}