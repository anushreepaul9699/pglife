<?php

//session start --->
    session_start() ;

//require function copies all of the text from a given file into the file that uses the include function -->
    require("../includes/database_connect.php");

//fetching user's input (email id , password ) --- >
    $email = $_POST['email'] ;
    $password = $_POST['password'] ;

//sha1() is predefined function , it is used to calculate the SHA-1 hash of a string -->
    $password = sha1($password) ;

//query to fetch user's details where email id = $email_id (fetched from SESSION variable) and password = $password (fetched from SESSION variable) -->
    $sql = "SELECT * FROM users where email = '$email' AND password = '$password' " ;

//storing the result of the query (whether T/F - query work or not) in $result --->
    $result = mysqli_query($conn , $sql) ;

//if not run (query not work) --->
    if(!$result)
    {
        $response = array ("success" => false, "message" => "Something went wrong!") ;
        echo json_encode($response) ;
        return ;
    }

//if query run successfully then store the row count in $row_count (to check whether the user has signup or not) -->
    $row_count = mysqli_num_rows($result) ;

//if count is equals to 0 ---> 
    if($row_count == 0)
    {
        $response = array("success" => false, "message" => "Login failed! Invalid email or password.");

        //The json_encode() function is used to encode a value to JSON format --->

        //JSON is used to send data from the server to the browser when you want to retain it's structure. 
        //It is easy to parse in Javascript with the result being an array that matches the original array in the server language (like PHP). 
        //For simple, single values, it isn't really needed.

        echo json_encode($response) ;
        return ; 

    }

//fetch the records of user's and store it in $row variable --->
    $row = mysqli_fetch_assoc($result) ;


    $_SESSION['user_id'] = $row['id'] ;
    $_SESSION['full_name'] = $row ['full_name'] ;
    $_SESSION['email'] = $row['email'] ;

//print the message (when user is logged in successfully) --->
    $response = array("success" => true, "message" => "Login successful!");
    echo json_encode($response);

//close the connection --->
    mysqli_close($conn) ;

   







