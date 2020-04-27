<?php

namespace App\Config;
use PDO;

Class Connection {
    private  $server = "mysql:host=localhost;dbname=apiagua";
    private  $user = "phpmyadmin";
    private  $pass = "tinoroot";
    private  $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
    protected $con;
     
    public function openConnection()
    {
        try{
            $this->con = new PDO($this->server, $this->user,$this->pass,$this->options);
            $this->con->exec("set names utf8");
            return $this->con;
        }
        catch (PDOException $e){
            echo "Problemas na conexão: " . $e->getMessage();
        }
    }

    public function closeConnection() {
        $this->con = null;
    }
}