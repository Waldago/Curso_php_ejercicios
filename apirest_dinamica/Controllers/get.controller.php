<?php 

require("Models/get.model.php");

class GetController{

    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){
        /*Peticion get sin filtro*/
        $response= GetModel::getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
        print_r($response);
        return;
        $return = new GetController();
        $return->fncResponse($response);
    }

    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        /* linkTo es la columna y el equalTo es el contenido de esa columna*/
        $response= GetModel::getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);
    }

    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){
        $responce = GetModel::getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($responce);
    }

    static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        $responce = GetModel::getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($responce);
     }

    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
        $responce = GetModel::getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($responce);
    }

    static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
        $responce = GetModel::getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return ->fncResponse($responce);
     }

     static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $inTo, $filterTo){
        $responce = GetModel::getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $inTo, $filterTo);
        $return = new GetController();
        $return -> fncResponse($responce);
     }

     static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $inTo, $filterTo){
        $responce = GetModel::getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $inTo, $filterTo);
        $return = new GetController();
        $return -> fncResponse($responce);
     }

    public function fncResponse($response){
        if(!empty($response)){
            $json = array (
                'status'=>200,
                'Total'=>count($response),
                'result'=>$response
            );
            
            echo json_encode($json, http_response_code($json["status"]));
        } else {
            $json = array (
                'status'=>404,
                'result'=>'Not Found'
            );
            
            echo json_encode($json, http_response_code($json["status"]));
        }
    }
}
?>