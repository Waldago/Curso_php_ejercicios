<?php 

    // Mostrar errores

    ini_set('display_errors',1);
    ini_set('log_errors', 1);
    ini_set('error_log', "./php_error_log");
    
    //Requerimientos
    require_once("./Models/connection.php");

    require_once("./Controllers/routes.controller.php");
    

    $index = new RoutesController();

    $index -> index();

?>