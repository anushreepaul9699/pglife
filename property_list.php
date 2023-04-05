<?php
session_start() ;
require "includes/database_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL ; 

//fetching user's input in the search box :--- >
$city_name = $_GET["city"] ;

//checking (city name is a valid city name or not )--->
$sql_1 = "SELECT * FROM cities where name = '$city_name' " ;

$result_1 = mysqli_query($conn , $sql_1) ;

if(!$result_1)
{
    echo "Something went wrong ! " ;
    return ;
}

//fetching associated array of the respected city --- >
$city = mysqli_fetch_assoc($result_1) ;

if(!$city)
{
    echo "Sorry! We do not have any PG listed in this city." ;
    return ;
}

//fetching city id --->
$city_id = $city['id'] ;

//selecting those properties from properties table whose city id is equal to the fetched city id : --->
$sql_2 = "SELECT * FROM properties where city_id = '$city_id' " ;

$result_2 = mysqli_query($conn , $sql_2) ;

if(!$result_2)
{
    echo "Something went wrong!" ;
    return ;
}

$properties = mysqli_fetch_all($result_2) ;


$sql_3 = "SELECT * FROM interested_users_properties iup 
         INNER JOIN properties p 
         ON iup.property_id = p.id 
         WHERE p.city_id = $city_id " ;

$result_3 = mysqli_query($conn , $sql_3) ;

if (!$result_3) {
    echo "Something went wrong!";
    return;
}

$interested_users_properties = mysqli_fetch_all($result_3, MYSQLI_ASSOC) ;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Best PG's in <?php echo $city_name ?> | PG Life</title>

    <?php

        include "includes/head_links.php" ;
    ?>

    <link href="css/property_list.css" rel="stylesheet" />
</head>

<body>
    
<?php
    include "includes/header.php" ;
?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $city_name ; ?>
            </li>
        </ol>
    </nav>

    <div class="page-container">
        <div class="filter-bar row justify-content-around">
            <div class="col-auto" data-toggle="modal" data-target="#filter-modal">
                <img src="img/filter.png" alt="filter" />
                <span>Filter</span>
            </div>
            <div class="col-auto">
                <img src="img/desc.png" alt="sort-desc" />
                <span>Highest rent first</span>
            </div>
            <div class="col-auto">
                <img src="img/asc.png" alt="sort-asc" />
                <span>Lowest rent first</span>
            </div>
        </div>

        <!-- storing the images in the form of arrays -->
        <?php
            foreach($properties as $property)
            {
                $property_images = glob("img/properties/" . $property['id'] . "/*") ;
            
        ?>

        <div class="property-card row">
            <div class="image-container col-md-4">
                <!-- access the first property images -->
                <img src="<?= $property_images[0] ?>" />
            </div>
            <div class="content-container col-md-8">
                <div class="row no-gutters justify-content-between">
                    <!-- calculating total rating by adding rating_food + rating_safety + rating_clean and then round it to 1 digit decimal -->
                    <?php

                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3 ; // taking average of 3 
                        $total_rating = round($total_rating,1) ;
                    
                    ?>

                    <div class="star-container" title="<?= $total_rating ?>">

                        <?php
                             
                             //initialising rating as total rating
                            $rating = $total_rating ;

                            //starting a loop
                            for($i = 0 ; $i <5 ; $i++)
                            {
                                //if rating >=$i + 0.8
                               if($rating >= $i + 0.8)
                               {
                            
                        ?>
                        
                        <!-- print this star -->
                        <i class="fas fa-star"></i>

                        <?php 
                            
                            }
                              //else if rating is >= $i + 0.3
                              elseif($rating >= $i + 0.3)

                            {

                        ?>
                        <!-- print this star -->
                        <i class="fas fa-star-half-alt"></i>

                        <?php
                            }

                            //else ----->
                            else {
                        ?>
                        <!-- print this star -->
                        <i class="far fa-star"></i>

                    <?php
                            }
                            
                        }

                     ?>
                    <!-- close the div -->  
                        
                    </div>
                    <div class="interested-container">
                        
                         <!-- set interested user count to 0 & is_interested as false -->
                        <?php
                           $interested_users_count = 0 ;
                           $is_interested = false ;

                           //foreach loop that iterate interested_users_properties (associative array : k - v ) 
                           foreach($interested_users_properties as $interested_user_property )
                           {
                               //if the recieved interested_user_property is equal to the property_id
                               if($interested_user_property['property_id'] == $property['id'])
                               {
                                 
                                  //increase user's count
                                  $interested_users_count++ ;
                                  
                                  //if recieved user's id == intereseted_user_id then is_interested is true 
                                  if($interested_user_property['user_id'] == $user_id)
                                  {
                                      $is_interested = true ;
                                  }
                               }

                            }
                           
                           //if the user's is interested in this property
                           if($is_interested)
                           {
                            
                            ?>
                           
                            <!-- print this icon -->
                            <i class="fas fa-heart"></i>

                        <?php
                           }

                           else 

                           {

                        ?>
                           <!-- else print this icon -->
                            <i class="far fa-heart"></i>

                        <?php
                           }

                        ?>
                           
                        <div class="interested-text"><?= $interested_users_count ?> interested</div>
                    </div>
                </div>
                <div class="detail-container">
                    <div class="property-name"><?= $property['name'] ?></div>
                    <div class="property-address"><?= $property['address'] ?></div>
                    <div class="property-gender">
                        <?php
                           
                            // if property's gender == male 
                           if($property['gender'] == "male")
                           {

                        ?>
                         <!-- print this icon -->
                        <img src="img/male.png" />

                        <?php
                           }
                           
                           //else if property's gender == femlae 
                           elseif ($property['gender'] == "female")
                           {

                           ?>
                            <!-- print this icon --->
                            <img src="img/female.png" />

                        <?php
                           }

                           else {
                        ?>
                         <!-- else print this icon -->
                        <img src="img/unisex.png" />

                        <?php
                           }
                        ?>

                    </div>
                </div>
                <div class="row no-gutters">
                    <div class="rent-container col-6">
                        <div class="rent">₹ <?= number_format($property['rent']) ?>/-</div>
                        <div class="rent-unit">per month</div>
                    </div>
                    <div class="button-container col-6">
                        <a href="property_detail.php ? property_id = <?= $property['id'] ?>" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
            }
            
            //if count of properties is 0 
            if (count($properties) == 0)
            {
        
        ?>

                <div class = "no-property-container" >

                <!-- no pg's to list as interested -->
                    <p> No PG to list </p>
                </div>
        <?php

          }

        ?>

    </div>
    <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filter-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="filter-heading">Filters</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5>Gender</h5>
                    <hr />
                    <div>
                        <button class="btn btn-outline-dark btn-active">
                            No Filter
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-venus-mars"></i>Unisex
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-mars"></i>Male
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-venus"></i>Female
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-success">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <?php

        include "includes/signup-modal.php" ;

    ?>

    <?php

        include "includes/login-modal.php" ;
    
    ?>

    <?php

        include "includes/footer.php" ;
    ?>

</body>

</html>
