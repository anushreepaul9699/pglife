<!-- To start a new Session in order to initiate a new HTTP request that caries small piece of information --->
<?php
session_start();
require "includes/database_connect.php";

// if the user is not logged in --->
if (!isset($_SESSION["user_id"])) 
{
    //go to the main page i.e., home page --->
    header("location: index.php");
    
    // die() function prints a message and exits the current script.:equivalent to exit()
    die();
}

//if the user is logged in then fetch the user's id (got from Session super global variable--->
$user_id = $_SESSION['user_id'];

//Selecting the records of the user's using user's id --->
$sql_1 = "SELECT * FROM users WHERE id = $user_id";

//mysqli_query() = run the sql query --->
$result_1 = mysqli_query($conn, $sql_1);

//if the query gives an error --->
if (!$result_1) {
    echo "Something went wrong!";
    return;
}

//fetch the user's detail that is obtained from the query --->
$user = mysqli_fetch_assoc($result_1);

//if no records were obtained --->
if (!$user) {
    echo "Something went wrong!";
    return;
}

//quer_2 : Select records from the interested user's properties 's table 
//that is inner join with the properties's table with respect to (iup.id = p.id) using user's id (to fetch the interested properties in the dashboard )--->
$sql_2 = "SELECT * 
            FROM interested_users_properties iup
            INNER JOIN properties p ON iup.property_id = p.id
            WHERE iup.user_id = $user_id";

//store the result of query run (T/F) in the result_2 variable -- >
$result_2 = mysqli_query($conn, $sql_2);

//if the result is F : ---->
if (!$result_2) {
    echo "Something went wrong!";
    return;
}

//fetch the interested properties of the user's in the interested_properties variables --->
$interested_properties = mysqli_fetch_all($result_2, MYSQLI_ASSOC);
?>

<!-- Start of HTML file --->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | PG Life</title>

    <!-- Headlinks includes in common file named includes -->
    <?php
    include "includes/head_links.php";
    ?>

    <!-- CSS file for dashboad.css -->
    <link href="css/dashboard.css" rel="stylesheet" />
</head>

<body>
    <!-- Header part for dashboad.php that in placed inside 'includes' file --> 
    <?php
    include "includes/header.php";
    ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Dashboard
            </li>
        </ol>
    </nav>
    
    <!-- My profile container --->
    <div class="my-profile page-container">
        <h1>My Profile</h1>
        <div class="row">
            <div class="col-md-3 profile-img-container">
                <i class="fas fa-user profile-img"></i>
            </div>
            <div class="col-md-9">
                <div class="row no-gutters justify-content-between align-items-end">

                <!-- Displaying User's Details --->
                    <div class="profile">
                        <div class="name"><?= $user['full_name'] ?></div>
                        <div class="email"><?= $user['email'] ?></div>
                        <div class="phone"><?= $user['phone'] ?></div>
                        <div class="college"><?= $user['college_name'] ?></div>
                    </div>

                    <div class="edit">
                        <div class="edit-profile">Edit Profile</div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- If the count of interested_properties is > 0 -->
    <?php
    if (count($interested_properties) > 0) {
    ?>
        <div class="my-interested-properties">
            <div class="page-container">
                <!-- display the my interested properties containers --->
                <h1>My Interested Properties</h1>

                <!-- fetching interested properties --->
                <?php
                foreach ($interested_properties as $property) 
                {
                    //storing all the properties's image files in array using glob function --->
                    $property_images = glob("img/properties/" . $property['id'] . "/*");
                ?>
                  
                   <!-- Display the properties using propertie's id from interested user's table --->
                    <div class="property-card property-id-<?= $property['id'] ?> row">
                        <div class="image-container col-md-4">
                            
                        <!-- fetching the first element of the array -->
                            <img src="<?= $property_images[0] ?>" />
                        </div>

                        <!-- Rating Part to display the rate star of the properties --->
                        <div class="content-container col-md-8">
                            <div class="row no-gutters justify-content-between">

                            <!-- Calculating total rating using rating_food + rating_safety + rating_clean /3 and round it to 1 decimal digit --->
                                <?php
                                $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                                $total_rating = round($total_rating, 1);
                                ?>

                                <div class="star-container" title="<?= $total_rating ?>">

                                <!-- Dispalying rate star (total 5 star that's why loop run from 0 to 4) -->
                                    <?php
                                    $rating = $total_rating;
                                    for ($i = 0; $i < 5; $i++) 
                                    {
                                        //if rating(total_rating) >= i + 0.8 : then print fas fa-star 
                                        if ($rating >= $i + 0.8) {
                                    ?>
                                       <i class="fas fa-star"></i>
                                        
                                    <?php
                                        } 
                                        //if rating(total_rating) >= i + 0.3: then print fas fa-star-half-alt
                                        elseif ($rating >= $i + 0.3) {
                                        ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        
                                        <!-- else print far fa-star --->
                                        <?php
                                        } else {
                                        ?>
                                            <i class="far fa-star"></i>
                                    <!-- closing loop --->
                                    <?php
                                        }
                                    }
                                    ?>

                                </div>

                                <!-- interested heart icon part --->
                                <div class="interested-container">
                                    <i class="is-interested-image fas fa-heart" property_id="<?= $property['id'] ?>"></i>
                                </div>
                            </div>

                            <!-- Gender icon part for the property --->
                            <div class="detail-container">
                                <div class="property-name"><?= $property['name'] ?></div>
                                <div class="property-address"><?= $property['address'] ?></div>
                                <div class="property-gender">
                                
                                <?php
                                    if ($property['gender'] == "male") {
                                ?>
                                        <img src="img/male.png">
                                <?php
                                    } elseif ($property['gender'] == "female") {
                                ?>
                                        <img src="img/female.png">
                                <?php
                                    } else {
                                ?>
                                        <img src="img/unisex.png">
                                <?php
                                    }
                                ?>


                                </div>
                            </div>

                            
                         <!-- Rent part of the property --->
                            <div class="row no-gutters">
                                <div class="rent-container col-6">
                                    <div class="rent">₹ <?= number_format($property['rent']) ?>/-</div>
                                    <div class="rent-unit">per month</div>
                                </div>
                                <div class="button-container col-6">
                                    <a href="property_detail.php?property_id=<?= $property['id'] ?>" class="btn btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- closing loop --->
                <?php
                }
                ?>

            </div>
        </div>
    
    <!-- closing loop --->
    <?php
    }
    ?>

<!-- footer part --->
    <?php
    include "includes/footer.php";
    ?>
<!-- javascript part to make the heart toggle work --->
    <script type="text/javascript" src="js/dashboard.js"></script>

<!-- End of body tag --->
</body>

</html><!-- End of HTML file --->
