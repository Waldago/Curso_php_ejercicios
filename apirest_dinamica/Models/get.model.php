<?php 
require_once ("connection.php");

class GetModel{

    static public function getData($table){
        $sql="SELECT * FROM $table";

        $stmt = Conection::connect()->prepare($sql);

        $stmt-> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}

?>