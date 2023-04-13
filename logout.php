<!-- To start a new Session in order to initiate a new HTTP request that caries small piece of information --->
<?php
 
    // start a session in order to get the user's id --->
    session_start() ;

    //destroying the session to remove the user temporarily from the server --->
    session_destroy() ;

    //redirrecting the page to the home page i.e index.php --->
    header("location: index.php");

