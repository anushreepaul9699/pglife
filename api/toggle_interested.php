<?php
   
   session_start();
    //checking database connectivity ---->
    require "../includes/database_connect.php" ;

    //if user id is not set : User is not logged in --- >
    if(!isset($_SESSION['user_id']) )
    {
        echo json_encode(array("success" => false, "is_logged_in" => false));
        return;
    }

    //fetching the user's id and property's id (required for toggling the interested icon  )---->
    $user_id = $_SESSION['user_id'] ;
    $property_id = $_SESSION['property_id'] ;

    //selecting records using user's id and properties's id ----->
    $sql_1 = "SELECT * FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id" ;
    $result_1 = mysqli_query($conn , $sql_1) ;

    if(!$result_1)
    {
        $response = array ("success" => false , "message" => "Something went wrong !") ;
        echo json_encode($response);
        return ;
    }

   
     // if the user is interested in the property ---->
   if(mysqli_num_rows($result_1) > 0)
   {
      //delete the entry from the interested users properties in order to toggle out the interested icon --->
      $sql_2 = "DELETE FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id " ;
      $result_2 = mysqli_query($conn , $sql_2);
      if(!$result_2)
      {
         echo json_encode(array("success" => false , "message"=> "Something went wrong ! "));
         return ;
      }

      else
      {
        //if toggle out successfully then mark is_interested as false --->
         echo json_encode( array("success" => true , "is_interested" => false , "property_id" => $property_id) );
         return ;
      }

    }

    else 
    {
        $sql_3 = "INSERT INTO interested_users_properties(user_id, property_id) VALUES ('$user_id' , '$property_id' )" ;
        $result_3 = mysqli_query($conn , $sql_3) ;

        if(!$result_3)
        {
            echo json_encode( array("success" => false , "message" => "Something went wrong !")) ;
            return ;
        }

        else 
        {
            echo json_encode( array("success" => true , "is_interested" => true , "property_id" => $property_id) );
            return ;
        }
    }


      
      



