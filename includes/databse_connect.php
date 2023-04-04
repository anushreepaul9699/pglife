<?php

    $db_hostname = "127.0.0.1" ;
    $db_user_name = "root" ;
    $db_password = "" ;
    $db_name = "pglife" ;

    $conn = mysqli_connect($db_hostname , $db_user_name , $db_password , $db_name) ;

    if(mysqli_connect_errno())
    {
        //Throw error message based on ajax or not 
        
        echo "Failed to connect to MySql ! Please contact the admin !" ;
        return ;
    }

    ?>