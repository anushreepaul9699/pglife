<!-- To start a new Session in order to initiate a new HTTP request that caries small piece of information --->
<?php
session_start();
require "includes/database_connect.php";

// fetching user's id--->
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

//fetching city's name using city tag from search bar --->
$city_name = $_GET["city"];

//fetching city's details using city_name --->
$sql_1 = "SELECT * FROM cities WHERE name = '$city_name'";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo "Something went wrong!";
    return;
}

//storing particular city's record in city variable --->
$city = mysqli_fetch_assoc($result_1);
if (!$city) {
    echo "Sorry! We do not have any PG listed in this city.";
    return;
}

//fetching city's id --->
$city_id = $city['id'];

//fetching properties using city's id --->
$sql_2 = "SELECT * FROM properties WHERE city_id = $city_id";
$result_2 = mysqli_query($conn, $sql_2);
if (!$result_2) {
    echo "Something went wrong!";
    return;
}

//storing properties records in properties variables -->
$properties = mysqli_fetch_all($result_2, MYSQLI_ASSOC);


// fetching interested properties using city_id --->
$sql_3 = "SELECT * 
            FROM interested_users_properties iup
            INNER JOIN properties p ON iup.property_id = p.id
            WHERE p.city_id = $city_id";
$result_3 = mysqli_query($conn, $sql_3);
if (!$result_3) {
    echo "Something went wrong!";
    return;
}

//storing interested user's properties in interested_users_properties variables --->
$interested_users_properties = mysqli_fetch_all($result_3, MYSQLI_ASSOC);
?>

<!-- start of html file --->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- printing city name get from city tag from the search bar --->
    <title>Best PG's in <?php echo $city_name ?> | PG Life</title>

    <!-- including head links --->
    <?php
    include "includes/head_links.php";
    ?>

    <!-- linking css file for property_list --->
    <link href="css/property_list.css" rel="stylesheet" />
</head>

<body>

<!-- including header part --->
    <?php
    include "includes/header.php";
    ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>

            <!-- printing city name in the breadcrumb --->
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $city_name; ?>
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

    <!-- fetching properties using city name --->
        <?php
        foreach ($properties as $property) {
            $property_images = glob("img/properties/" . $property['id'] . "/*");
        ?>
        <!-- fetching property's image --->
            <div class="property-card property-id-<?= $property['id'] ?> row">
                <div class="image-container col-md-4">
                    <img src="<?= $property_images[0] ?>" />
                </div>
                <div class="content-container col-md-8">
                    <div class="row no-gutters justify-content-between">

                    <!-- fetching total rating for star rating icon --->
                        <?php
                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                        $total_rating = round($total_rating, 1);
                        ?>
                        <div class="star-container" title="<?= $total_rating ?>">
                            <?php
                            $rating = $total_rating;
                            for ($i = 0; $i < 5; $i++) {
                                if ($rating >= $i + 0.8) {
                            ?>
                                    <i class="fas fa-star"></i>
                                <?php
                                } elseif ($rating >= $i + 0.3) {
                                ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php
                                } else {
                                ?>
                                    <i class="far fa-star"></i>
                            <?php
                                }
                            }
                            ?>
                        </div>

                        <!-- fetching interested user's count and user is interested or not (for heart icon) -->
                        <div class="interested-container">
                            <?php
                            $interested_users_count = 0;
                            $is_interested = false;
                            foreach ($interested_users_properties as $interested_user_property) {
                                if ($interested_user_property['property_id'] == $property['id']) {
                                    $interested_users_count++;

                                    if ($interested_user_property['user_id'] == $user_id) {
                                        $is_interested = true;
                                    }
                                }
                            }

                            // if user is interested print fill heart icon  using property's id from interested_user's_properties table
                            if ($is_interested) {
                            ?>
                            
                                <i class="is-interested-image fas fa-heart" property_id="<?= $property['id'] ?>"></i>

                            <!--if user is not interested print empty heart icon  using property's id from interested_user's_properties table --->
                            <?php
                            } else {
                            ?>
                                <i class="is-interested-image far fa-heart" property_id="<?= $property['id'] ?>"></i>

                            <?php
                            }
                            ?>

                            <!-- printing interested user's count --->
                            <div class="interested-text">
                                <span class="interested-user-count"><?= $interested_users_count ?></span> interested
                            </div>
                        </div>
                    </div>

                    <!-- printing propertie's details --->
                    <div class="detail-container">
                        <div class="property-name"><?= $property['name'] ?></div>
                        <div class="property-address"><?= $property['address'] ?></div>
                        <div class="property-gender">

                        <!-- gender icon part --->
                            <?php
                            if ($property['gender'] == "male") {
                            ?>
                                <img src="img/male.png" />
                            <?php
                            } elseif ($property['gender'] == "female") {
                            ?>
                                <img src="img/female.png" />
                            <?php
                            } else {
                            ?>
                                <img src="img/unisex.png" />
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- rent part for the properties --->
                    <div class="row no-gutters">
                        <div class="rent-container col-6">
                            <div class="rent">₹ <?= number_format($property['rent']) ?>/-</div>
                            <div class="rent-unit">per month</div>
                        </div>

                        <!-- view details part using property id --->
                        <div class="button-container col-6">
                            <a href="property_detail.php?property_id=<?= $property['id'] ?>" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            </div>
        
        <!-- if interested user's count = 0 --->
        <?php
        }

        if (count($properties) == 0) {
        ?>
            <div class="no-property-container">
                <p>No PG to list</p>
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

    <!-- including common files (signup modal , login modal , footer) -->

    <?php
    include "includes/signup_modal.php";
    include "includes/login_modal.php";
    include "includes/footer.php";
    ?>

    <!-- including javascript files to make toogle heart icon work --->

    <script type="text/javascript" src="js/property_list.js"></script>

</body><!-- end of body tag -->

</html><!-- end of html file --->
