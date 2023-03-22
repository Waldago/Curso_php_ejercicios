<?php 
require("Controllers/get.controller.php");
//La funcion explode(): Devuelve un array de string, siendo cada uno un substring del parámetro string 
//formado por la división realizada por los delimitadores indicados en el parámetro string separator.
$table= explode("?", $routesArray[1])[0];
//?? funciona como un "sino hace tal cosa". En este caso si el atributo del select esta vacio, va a poner un *
$select= $_GET["select"] ?? "*";

$orderBy = $_GET["orderBy"] ?? null;

$orderMode = $_GET["orderMode"] ?? null;

$startAt = $_GET["startAt"] ?? null;

$endAt = $_GET["endAt"] ?? null;

$rel = $_GET["rel"] ?? null;

$type = $_GET["type"] ?? null;

//echo $select;
/*Respuesta del controlador, me fijo que variables vienen y que metodo va a usar */

$response=new GetController();


if($rel == null && $type == null && $table != "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
    /*Peticion get con filtro*/
    //echo "1";
    $response->getDataFilter($table, $select, $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);
}
else if($rel != null && $type != null && $table == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"])){
    /*Peticion get sin filtro con join*/
    //echo "2";
    $response->getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt);
}
else if($rel != null && $type != null && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
    /*Peticion get con filtro con join*/
    //echo "3";
    $response->getRelDataFilter($rel, $type, $select, $_GET["linkTo"], $_GET["equalTo"],$orderBy, $orderMode, $startAt, $endAt);
}
else if($rel == null && $type == null && $table != "relations" && isset($_GET["search"]) && isset($_GET["linkTo"])){
    //echo "4";
    $response->getDataSearch($table, $select, $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);
}
else if($rel != null && $type != null && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["search"])){
    /*Peticion get con filtro con join*/
    //echo "5";
    $response->getRelDataSearch($rel, $type, $select, $_GET["linkTo"], $_GET["search"],$orderBy, $orderMode, $startAt, $endAt);
}
else if(isset($_GET["between1"]) && isset($_GET["between2"]) && isset($_GET["linkTo"])){
    //PETICIONES GET CON SELECCION DE RANGOS
    $response->getDataRange($table, $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt);
}
else{
    /*Peticion get sin filtro*/
    //echo "me fui";
    $response->getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
}



/*
$json = array (
    'status'=>200,
    'result'=>'Solicitud GET'
);

echo json_encode($json, http_response_code($json["status"]));
*/
?>