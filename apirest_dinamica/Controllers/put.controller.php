<?php
require_once("Models/put.model.php");

class PutController{

    static public function putData($table,$data,$id,$nameid){
        $response = PutModel::putData($table, $data,$id,$nameid);
        $return = new PutController();
        $return -> fncResponse($response);
    }

    public function fncResponse($response){
        if(!empty($response)){
            $json = array (
                'status'=>200,
                'Metodo'=>$_SERVER['REQUEST_METHOD'],
                'result'=>$response
            );
            
            echo json_encode($json, http_response_code($json["status"]));
        } else {
            $json = array (
                'status'=>404,
                'result'=>'Not Found',
                'Metodo'=>$_SERVER['REQUEST_METHOD']
            );
            
            echo json_encode($json, http_response_code($json["status"]));
        }
    }
}