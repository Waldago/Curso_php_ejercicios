<?php 
require_once ("connection.php");

class GetModel{
    /*Peticion get sin filtro*/
    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){
        $selectArray=explode(",",$select);
        //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
        if(empty(Conection::getColumnsData($table,$selectArray))){
            return null;
        }
        /*Peticion get sin filtro pero ordenada*/
        if($orderBy != null and $orderMode != null){
            if($startAt == null and $endAt == null){
                /*Peticion get sin filtro ordenada pero sin limite*/
                $sql="SELECT $select FROM $table ORDER BY $orderBy $orderMode";
            }else{
                /*Peticion get sin filtro ordenada con limite*/
                $sql="SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            
        } else{
            if($startAt == null and $endAt == null){
                /*Peticion get sin filtro, sin orden y sin limite*/
                $sql="SELECT $select FROM $table";
            }else{
                /*Peticion get sin filtro con limite*/
                $sql="SELECT $select FROM $table LIMIT $startAt, $endAt";
            }
            
        }
        $stmt = Conection::connect()->prepare($sql);

        $stmt-> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        $selectArray=explode(",",$select); 
        $linkToArray = explode(",", $linkTo);
        foreach($linkToArray as $key=>$value){
            array_push($selectArray,$value);
        }
        $selectArray=array_unique($selectArray);
        //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
         if(empty(Conection::getColumnsData($table,$selectArray))){
            return null;
        }
        /*Peticion get con filtro*/
        
        $equalToArray = explode("_", $equalTo);
        $linkToTxt = "";

        //Esto va a concatenar con un and los filtros que recibamos a partir de tener mas de 1
        if(count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key>0){
                    $linkToTxt .= "AND ".$value." = :".$value." ";
                }
            }
        }

        /*Peticion get con filtro y ordenada*/
        if($orderBy != null and $orderMode != null){
            /*Peticion get con filtro y ordenada pero sin limite*/
            if($startAt == null and $endAt == null){
                $sql= "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt ORDER BY $orderBy $orderMode";
            }else {
                /*Peticion get con filtro y ordenada con limite*/
                $sql= "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }   
        }else{
            /*Peticion get con filtro sin orden*/
            if($startAt == null and $endAt == null){
                /*Peticion get con filtro sin orden y sin limite*/
                $sql="SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt";
            }else{
                /*Peticion get con filtro sin orden pero con limite*/
                $sql="SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt LIMIT $startAt, $endAt";
            }
            
        }
        
        $stmt = Conection::connect()->prepare($sql);
        //Aca enlazo el nombre de la columna con lo que quiero que contenga
        foreach($linkToArray as $key => $value){
            $stmt-> bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
        }
                
        $stmt-> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    /*Peticion get con join sin filtro*/
    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinTxt= "";  

        if(count($relArray)>1){
            foreach($relArray as $key => $value){
                //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
                if(empty(Conection::getColumnsData($value,["*"]))){
                    return null;
                    }
                if($key>0){
                    $innerJoinTxt .= "INNER JOIN ".$value." ON ".$relArray[$key-1].".".$typeArray[$key-1]." = ".$relArray[$key].".".$typeArray[$key];
                }
            }
        
        /*Peticion get sin filtro pero ordenada*/
        if($orderBy != null and $orderMode != null){
            if($startAt == null and $endAt == null){
                /*Peticion get sin filtro ordenada pero sin limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt ORDER BY $orderBy $orderMode";
            }else{
                /*Peticion get sin filtro ordenada con limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            
        } else{
            if($startAt == null and $endAt == null){
                /*Peticion get sin filtro, sin orden y sin limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt";
            }else{
                /*Peticion get sin filtro con limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt LIMIT $startAt, $endAt";
            }
            
        }
        $stmt = Conection::connect()->prepare($sql);
                 
        TRY{
            $stmt-> execute();
        }catch(PDOException $E){
            return null;
        }
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        }
        else return null;
    }

    static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinTxt= "";
        $linkToArray = explode(",", $linkTo);
        $equalToArray = explode("_", $equalTo);
        $linkToTxt = "";
        
        if(count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key>0){
                    $linkToTxt .= "AND ".$value." = :".$value." ";
                }
            }
        }

        if(count($relArray)>1){
            foreach($relArray as $key => $value){
                //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
                if(empty(Conection::getColumnsData($value,["*"]))){
                    return null;
                }
                if($key>0){
                    $innerJoinTxt .= "INNER JOIN ".$value." ON ".$relArray[$key-1].".".$typeArray[$key-1]." = ".$relArray[$key].".".$typeArray[$key];
                }
            }

            //Esto va a concatenar con un and los filtros que recibamos a partir de tener mas de 1
        
            
        /*Peticion get sin filtro pero ordenada*/
        if($orderBy != null and $orderMode != null){
            if($startAt == null and $endAt == null){
                /*Peticion get sin filtro ordenada pero sin limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt ORDER BY $orderBy $orderMode";
            }else{
                /*Peticion get sin filtro ordenada con limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            
        } else{
            if($startAt == null and $endAt == null){
                /*Peticion get sin filtro, sin orden y sin limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt";
            }else{
                /*Peticion get sin filtro con limite*/
                $sql="SELECT $select FROM $relArray[0] $innerJoinTxt WHERE $linkToArray[0] = :$linkToArray[0] $linkToTxt LIMIT $startAt, $endAt";
            }
            
        }
        $stmt = Conection::connect()->prepare($sql);
                //Aca enlazo el nombre de la columna con lo que quiero que contenga
        foreach($linkToArray as $key => $value){
            $stmt-> bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
        }

        TRY{
            $stmt-> execute();
        }catch(PDOException $E){
            return null;
        }
        
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        }
        else return null;
    }

    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
        $selectArray=explode(",",$select);
        /*Peticion get con busqueda*/
        $linkToArray = explode(",", $linkTo);
        $searchToArray = explode("_", $search);
        $linkToTxt = "";

        foreach($linkToArray as $key=>$value){
            array_push($selectArray,$value);
        }
        $selectArray=array_unique($selectArray);
        
        //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
        if(empty(Conection::getColumnsData($table,$selectArray))){
        return null;
        }

        //Esto va a concatenar con un and los filtros que recibamos a partir de tener mas de 1
        if(count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key>0){
                    $linkToTxt .= "AND ".$value." = :".$value." ";
                }
            }
        }

        $sql= "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToTxt";

        if($orderBy != null && $orderMode != null){
            if($startAt == null && $endAt == null){
                /*Peticion get con busqueda ordenada pero sin limite*/
                $sql=$sql."ORDER BY $orderBy $orderMode";
            }else{
                /*Peticion get con busqueda ordenada con limite*/
                $sql=$sql."ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }

        }else if($startAt != null && $endAt != null){
            $sql=$sql."LIMIT $startAt, $endAt";
        }
        $stmt = Conection::connect()->prepare($sql);
        //Aca enlazo el nombre de la columna con lo que quiero que contenga
        foreach($linkToArray as $key => $value){
            if($key>0){
                $stmt-> bindParam(":".$value, $searchToArray[$key], PDO::PARAM_STR);
            }
        }
        $stmt-> execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinTxt= "";
        $linkToArray = explode(",", $linkTo);
        $searchToArray = explode("_", $search);
        $linkToTxt = "";
              
        if(count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key>0){
                    $linkToTxt .= "AND ".$value." = :".$value." ";
                }
            }
        }

        if(count($relArray)>1){
            //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
            if(empty(Conection::getColumnsData($value,["*"]))){
                return null;
                }
            foreach($relArray as $key => $value){
                if($key>0){
                    $innerJoinTxt .= "INNER JOIN ".$value." ON ".$relArray[$key-1].".".$typeArray[$key-1]." = ".$relArray[$key].".".$typeArray[$key];
                }
            }

        //Esto va a concatenar con un and los filtros que recibamos a partir de tener mas de 1
        
        $sql="SELECT $select FROM $relArray[0] $innerJoinTxt WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToTxt";
            
        /*Peticion get join con busqueda pero ordenada*/
        if($orderBy != null and $orderMode != null){
            if($startAt == null and $endAt == null){
                /*Peticion get join con busqueda ordenada pero sin limite*/
                $sql=$sql." ORDER BY $orderBy $orderMode";
            }else{
                /*Peticion get join con busqueda ordenada con limite*/
                $sql=$sql." ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            
        } else if($startAt != null and $endAt != null){
                /*Peticion get join con busqueda con limite*/
                $sql=$sql." LIMIT $startAt, $endAt";
        }
        $stmt = Conection::connect()->prepare($sql);

        //Aca enlazo el nombre de la columna con lo que quiero que contenga
        foreach($linkToArray as $key => $value){
            if($key>0){
                $stmt-> bindParam(":".$value, $searchToArray[$key], PDO::PARAM_STR);
            }
        }
        
        TRY{
            $stmt-> execute();
        }catch(PDOException $E){
            return null;
        }
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        }
        else return null;
    }

    static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $inTo, $filterTo){
        $selectArray=explode(",",$select);
        array_push($selectArray,$linkTo);
                
        //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
        if(empty(Conection::getColumnsData($table,$selectArray))){
        return null;
        }
        
        
        $sql="SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' and '$between2'";

        if($inTo != null && $filterTo != null){
            $sql = $sql."AND $inTo IN ($filterTo)";
            /*Peticion get join con busqueda pero ordenada*/
            if($orderBy != null and $orderMode != null){
                if($startAt == null and $endAt == null){
                    /*Peticion get join con busqueda ordenada pero sin limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode";
                }else{
                    /*Peticion get join con busqueda ordenada con limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
                }
                
            } else if($startAt != null and $endAt != null){
                    /*Peticion get join con busqueda con limite*/
                    $sql=$sql." LIMIT $startAt, $endAt";
            }
        }else{
            /*Peticion get join con busqueda pero ordenada*/
            if($orderBy != null and $orderMode != null){
                if($startAt == null and $endAt == null){
                    /*Peticion get join con busqueda ordenada pero sin limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode";
                }else{
                    /*Peticion get join con busqueda ordenada con limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
                }
                
            } else if($startAt != null and $endAt != null){
                    /*Peticion get join con busqueda con limite*/
                    $sql=$sql." LIMIT $startAt, $endAt";
            }
        }
        
        $stmt = Conection::connect()->prepare($sql);
                
        $stmt-> execute();
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);

    }

    static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $inTo, $filterTo){
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinTxt= "";
                
        if(count($relArray)>1){
            foreach($relArray as $key => $value){
                //ESTO VERIFICA QUE LA TABLA QUE ESTAMOS TRAYENDO EXISTE
                if(empty(Conection::getColumnsData($value,["*"]))){
                    return null;
                    }
                if($key>0){
                    $innerJoinTxt .= "INNER JOIN ".$value." ON ".$relArray[$key-1].".".$typeArray[$key-1]." = ".$relArray[$key].".".$typeArray[$key];
                }
            }
        
        $sql="SELECT $select FROM $relArray[0] $innerJoinTxt WHERE $relArray[0].$linkTo BETWEEN '$between1' and '$between2'";

        if($inTo != null && $filterTo != null){
            $sql = $sql."AND $inTo IN ($relArray[0].$filterTo)";
            /*Peticion get join con busqueda pero ordenada*/
            if($orderBy != null and $orderMode != null){
                if($startAt == null and $endAt == null){
                    /*Peticion get join con busqueda ordenada pero sin limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode";
                }else{
                    /*Peticion get join con busqueda ordenada con limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
                }
                
            } else if($startAt != null and $endAt != null){
                    /*Peticion get join con busqueda con limite*/
                    $sql=$sql." LIMIT $startAt, $endAt";
            }
        }else{
            /*Peticion get join con busqueda pero ordenada*/
            if($orderBy != null and $orderMode != null){
                if($startAt == null and $endAt == null){
                    /*Peticion get join con busqueda ordenada pero sin limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode";
                }else{
                    /*Peticion get join con busqueda ordenada con limite*/
                    $sql=$sql." ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
                }
                
            } else if($startAt != null and $endAt != null){
                    /*Peticion get join con busqueda con limite*/
                    $sql=$sql." LIMIT $startAt, $endAt";
            }
        }
        $stmt = Conection::connect()->prepare($sql);
                
        TRY{
            $stmt-> execute();
        }catch(PDOException $E){
            return null;
        }
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);

        } else return null;


    }
}

?>