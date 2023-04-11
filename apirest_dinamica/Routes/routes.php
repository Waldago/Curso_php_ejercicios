<?php 
    //Armo un array con todo lo que este cerca de la "/"    
    $routesArray=explode("/",$_SERVER['REQUEST_URI']);
    //Limpio el array
    $routesArray=array_filter($routesArray);
    
    //Cuando no se hace una peticion a la api

    if(empty($routesArray)){
        $json = array (
            'status'=>404,
            'result'=>'Not Found'
        );
    
        echo json_encode($json, http_response_code($json["status"]));
    
        return;
    }

    //Cuando se hace una peticion a la api
    
    if(!empty($routesArray) && isset($_SERVER['REQUEST_METHOD'] )){
        $table = explode("?", $routesArray[1])[0];
        // echo '<pre>'; print_r($_SERVER['REQUEST_METHOD']); echo '</pre>';

        if($_SERVER['REQUEST_METHOD']=='GET'){
            include("Service/get.php");
        }

        if($_SERVER['REQUEST_METHOD']=='POST'){
            // $json = array (
            //     'status'=>200,
            //     'result'=>'Solicitud POST'
            // );
        
            // echo json_encode($json, http_response_code($json["status"]));
        
            // return;
            include("Service/post.php");
        }

        if($_SERVER['REQUEST_METHOD']=='PUT'){
            $json = array (
                'status'=>200,
                'result'=>'Solicitud PUT'
            );
        
            echo json_encode($json, http_response_code($json["status"]));
        
            return;
        }

        if($_SERVER['REQUEST_METHOD']=='DELETE'){
            $json = array (
                'status'=>200,
                'result'=>'Solicitud DELETE'
            );
        
            echo json_encode($json, http_response_code($json["status"]));
        
            return;
        }
    }
    
    
?>