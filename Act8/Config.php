<?php

    define("DBUSER","root");
    define("DBHOST","localhost");
    define("PASSWORD","");
    define("DB","actividad8");

    function conecta()
    {
        $con=mysqli_connect(DBHOST,DBUSER, PASSWORD, DB);
        
        if(!$con){
            mysqli_connect_error();
            mysqli_connect_errno();
            echo "No se pudo acceder a la base de datos";
        }
        return $con;
    }

?>