<?php

    class Conection{

        /*Informacion de la base de datos*/
        static public function infoDataBase(){
            $infoDB = array(
                    "database"=>"base",
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
        static public function getColumnsData($table,$colunms){
            $database = Conection::infoDataBase()["database"];
            
            $validate = Conection::connect()
            ->query("SELECT COLUMN_NAME AS ITEM FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
            ->fetchAll(PDO::FETCH_OBJ); 
            
            if(empty($validate)){
                return null;
            }else{
                if($colunms[0]!="*"){
                    $contador=0;
                    foreach($validate as $key=>$value){
                        $contador+=in_array($value->ITEM,$colunms);
                    }
                    /*
                    if(count($colunms)==$contador){
                        return $validate;
                    }else{
                        return null;
                    }
                    Esto se puede escribir asi: */
                    return $contador == count($colunms) ? $validate : null;
                }else {
                    $contador=0;
                    foreach($validate as $key=>$value){
                        $contador+=in_array($value->ITEM,$colunms);
                    }
                    /*
                    if(count($colunms)==$contador){
                        return $validate;
                    }else{
                        return null;
                    }
                    Esto se puede escribir asi: */
                    return $contador == (count($colunms)-1) ? $validate : null;
                }
                
            }
        }

    }

    




?>