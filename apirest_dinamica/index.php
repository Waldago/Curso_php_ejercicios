<?php 

    // Mostrar errores

    ini_set('display_errors',1);
    ini_set('log_errors', 1);
    ini_set('error_log', "C:/xampp/htdocs/Curso_php_ejercicios/apirest_dinamica/php_error_log");
    
    //Requerimientos
    require_once("models/connection.php");
    echo '<pre>';
    print_r(Conection::connect());
    echo '</pre>';

    require_once("Controllers/routes.controller.php");
    

    $index = new RoutesController();

    $index -> index();

?>