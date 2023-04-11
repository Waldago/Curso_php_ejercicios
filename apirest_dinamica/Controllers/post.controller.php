<?php
require_once("Models/post.model.php");

class PostController{

    static public function postData($table,$data){
        
        $response = PostModel::postData($table, $data);
        $return = new PostController();
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