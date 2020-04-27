<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

function parsingUrl(String $url){
    $url = parse_url($url);
    return explode('/', $url['path']);
}
