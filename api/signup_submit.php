<?php
    require "../includes/database_connect.php";
    
    // fetching user's input --->
    $full_name = $_POST['full_name'] ;
    $phone = $_POST['phone'] ;
    $email = $_POST['email'] ;
    //here we fetch the password : -->
    $password = $_POST['password'] ;
    
    //then we just encrypt the password using sha1 function then store the encrypted password in the database 
    $password = sha1($password) ;

    $college_name = $_POST['college_name'] ;
    $gender = $_POST['gender'] ;

    //to select rows that have the given email id --- >
    $sql = "SELECT * FROM users where email = '$email' " ;

    $result = mysqli_query($conn , $sql) ;

    if(!$result)
    {
        echo "Something went wrong !" ;
        exit ;

    }

    //if row count not = 0 : email id already exists --->
    $row_count = mysqli_num_rows($result);

    if ($row_count != 0)
    {
        echo "The email is already registered with us ! " ;
        exit ;
    }

    //else --> Insert the user's input in the database : --->
    $sql = "INSERT INTO users(email, password, full_name, phone, gender, college_name) VALUES ('$email','$password','$full_name','$phone','$gender','$college_name')" ;

   $result = mysqli_query($conn , $sql) ;

   if(!$result)
   {
      echo "Something went wrong !" ;
      exit ;
   }

   echo "Your account has been created successfully !" ;

   ?>

   Click <a href = "../index.php">here </a>to continue.
   <?php
    mysqli_close($conn) ;

    

