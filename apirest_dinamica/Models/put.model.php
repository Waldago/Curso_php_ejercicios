<?php
require_once("connection.php");
require_once("get.model.php");

class PutModel{

    public static function putData($table,$data,$id,$nameid){
        
        $response = GetModel::getDataFilter($table, $nameid, $nameid, $id, null, null, null, null);
        
        if(empty($response)){
            return null;
        }else{
            $set="";

            foreach($data as $key=>$value){
                $set .= $key." = :".$key.",";
            }

            $set = substr($set,0,-1);

            $sql= "UPDATE $table SET $set WHERE $nameid = :$nameid";
            //UPDATE table_name SET column1 = value1, column2 = value2, ... WHERE condition; 

            $link=Conection::connect();

            $stmt= $link->prepare($sql);

            foreach($data as $key=>$value){
                $stmt -> bindParam(":".$key, $data[$key], PDO::PARAM_STR);
            }

            $stmt -> bindParam(":".$nameid, $id, PDO::PARAM_STR);

            if($stmt -> execute()){
                $response = array(
                    "comment"=>"Los datos se actualizaron correctamente"
                );
                return $response;
            } else {
                return Conection::connect()->errorInfo();
            }
        }
        
    }
}