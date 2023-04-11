<?php
require_once("Models/connection.php");
require_once("Controllers/post.controller.php");

if(isset($_POST)){
    $columnsArray= array();
    foreach(array_keys($_POST) as $key=>$value){
        array_push($columnsArray,$value);
    }
    //print_r();
    //VALIDO TABLA Y COLUMNAS
    if(empty(Conection::getColumnsData($table,$columnsArray))){
        $json = array (
            'status'=>404,
            'result'=>'Error: LOS CAMPOS EN EL FORMULARIO NO MATCHEAN CON LA BASE DE DATOS'
        );
        echo json_encode($json, http_response_code($json["status"]));
        return;
    }else {
        $response = new PostController();
        $response -> postData($table,$_POST);
    }
    //echo "entre";
}
?>