<?php
require_once("Models/connection.php");
require("Controllers/put.controller.php");

$response = new PutController();

if(isset($_GET["id"]) && isset($_GET["nameid"])){
    $data = array();

    parse_str(file_get_contents('php://input'),$data);
    
    $columns= array();
    
    foreach($data as $key=>$value){
    array_push($columns, $key);
    }

    array_push($columns, $_GET["nameid"]);

    $columns = array_unique($columns);

    if(empty(Conection::getColumnsData($table,$columns))){
        $json = array (
            'status'=>404,
            'result'=>'LA TABLA O LAS COLUMNAS NO EXISTEN'
        );
    
        echo json_encode($json, http_response_code($json["status"]));
    
        return;

    }else{

        $response->putData($table,$data,$_GET["id"],$_GET["nameid"]);

    }

}
