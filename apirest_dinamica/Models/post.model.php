<?php
require_once("connection.php");

class PostModel{

    static public function postData($table,$data){
        $columns="";
        $params="";

        foreach($data as $key=>$value){
            $columns .= $key.",";
            $params .= ":".$key.",";
        }

        $columns= substr($columns,0,-1);
        $params = substr($params, 0, -1);
        
        $sql="INSERT INTO $table ($columns) VALUES ($params)";

        $link=Conection::connect();

        $stmt= $link->prepare($sql);

        foreach($data as $key=>$value){
            $stmt-> bindParam(":".$key, $data[$key], PDO::PARAM_STR);
        }
        
        if($stmt-> execute()){
            $response = array(
                "lastID"=> $link->lastInsertId(),
                "comment"=>"Los datos se insertaron correctamente"
            );
            return $response;
        } else {
            return Conection::connect()->errorInfo();
        }
    }
}