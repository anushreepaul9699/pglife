<!-- To start a new Session in order to initiate a new HTTP request that caries small piece of information --->
<?php
session_start() ;
?>

<!-- Start of HTML file --->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | PG Life</title>

    <?php

        //includes head links (common head links) --->
        include "includes/head_links.php" ;
    
    ?>

    <!-- include home's page css file --->
    <link href="css/home.css" rel="stylesheet" />
</head>

<body>
    
    <?php

        //include header part --->
        include "includes/header.php" ;
    ?>

     <!-- main dispaly part in index.php -->
    <div class="banner-container">
        <h2 class="white pb-3">Happiness per Square Foot</h2>

        <!-- search bar (GET Method used ,action = property_list.php )--->
        <form method = "GET" id="search-form" action = "property_list.php">
           
           <!-- search bar to search a city (name = city )-->
            <div class="input-group city-search">
                <input type="text" class="form-control input-city" id='city' name='city' placeholder="Enter your city to search for PGs" />
                <div class="input-group-append">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- second part of the index.php page where it display major cities -->
    <div class="page-container">
        <h1 class="city-heading">
            Major Cities
        </h1>
        <div class="row">
            <div class="city-card-container col-md">

            <!-- For city_name = 'Delhi' --->
                <a href="property_list.php?city=Delhi">
                    <div class="city-card rounded-circle">
                        <img src="img/delhi.png" class="city-img" />
                    </div>
                </a>
            </div>

            <!-- For city_name = 'Mumbai' --->
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Mumbai">
                    <div class="city-card rounded-circle">
                        <img src="img/mumbai.png" class="city-img" />
                    </div>
                </a>
            </div>

            <!-- For city_name = 'Bengaluru' --->
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Bengaluru">
                    <div class="city-card rounded-circle">
                        <img src="img/bangalore.png" class="city-img" />
                    </div>
                </a>
            </div>

            <!-- For city_name = 'Hyderabad' --->
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Hyderabad">
                    <div class="city-card rounded-circle">
                        <img src="img/hyderabad.png" class="city-img" />
                    </div>
                </a>
            </div>
        </div>
    </div>

<!-- Signup Modal --->
  <?php
    include "includes/signup_modal.php" ;
  ?>

<!-- Login Modal -->
    <?php

        include "includes/login_modal.php" ;
    ?>

<!-- Footer part --->
   <?php

     include "includes/footer.php" ;
    
    ?>

</body> <!--End of Body tag--->

</html> <!-- End of html page --->
