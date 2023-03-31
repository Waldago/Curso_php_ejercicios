<?php

    class Conection{

        /*Informacion de la base de datos*/
        static public function infoDataBase(){
            $infoDB = array(
                    "database"=>"base-1",
                    "user"=>"root",
                    "pass"=>""
            );
            return $infoDB;
        }

        /*Conexion a la base de datos*/
        static public function connect(){
            try{
                $link = new PDO("mysql:host=localhost;dbname=".Conection::infoDataBase()["database"], 
                Conection::infoDataBase()["user"], 
                Conection::infoDataBase()["pass"]
                );

                $link -> exec("set names utf8");
            }
            catch(PDOException $e){
                /*die lo que hace es matar la ejecucion y puede mostrar un texto*/
                die("Error: ".$e->getMessage());
            }

            return $link;
        }

        //Validar la existencia de una tabla en la base de datos
        static public function getColumnsData($table){
            $database = Conection::infoDataBase()["database"];
            return Conection::connect()
            ->query("SELECT COLUMN_NAME AS ITEM FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
            ->fetchAll(PDO::FETCH_OBJ); 
        }

    }

    




?>