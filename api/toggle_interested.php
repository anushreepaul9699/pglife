<?php

//session start to fetch/send  some small piece of information --->
session_start();

require "../includes/database_connect.php";

//if the user is not logged in --->
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "is_logged_in" => false));
    return;
}

//if user is logged in then fetch the user's id and property's id (in which user want to mark as interested ) --->
$user_id = $_SESSION['user_id'];
$property_id = $_GET["property_id"];

//query to select records from interested users properties table using user's id & property's id --->
$sql_1 = "SELECT * FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo json_encode(array("success" => false, "message" => "Something went wrong"));
    return;
}

//if user has marked the property as interested --->
if (mysqli_num_rows($result_1) > 0) 
{
    //delete the record of the user from the interested user's properties in order to unmark the interested icon (using user's id and property's id )--->
    $sql_2 = "DELETE FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id";
    $result_2 = mysqli_query($conn, $sql_2);

    //if query not work --->
    if (!$result_2) 
    {
        echo json_encode(array("success" => false, "message" => "Something went wrong"));
        return;
    } 

    //else property is successfully unmarked from interested part --->
    else 
    {
        echo json_encode(array("success" => true, "is_interested" => false, "property_id" => $property_id));
        return;
    }

} 

//if the user has not marked the property as interested --->
else 
    {

        //add the records (user's id & property's id) in the interested user's properties table --->
        $sql_3 = "INSERT INTO interested_users_properties (user_id, property_id) VALUES ('$user_id', '$property_id')";
        $result_3 = mysqli_query($conn, $sql_3);

        //if query not work --->
        if (!$result_3) {
            echo json_encode(array("success" => false, "message" => "Something went wrong"));
            return;
        } 

        //user has successfully mark the property as interested -->
        else 
        {
            echo json_encode(array("success" => true, "is_interested" => true, "property_id" => $property_id));
            return;
        }
        
}
