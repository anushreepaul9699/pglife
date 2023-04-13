<!-- To start a new Session in order to initiate a new HTTP request that caries small piece of information --->
<?php
session_start();
require "includes/database_connect.php";

//fetching user's id --->
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

//fetching property_id from previous page view button --->
$property_id = $_GET["property_id"];

//query to fetch propertie's details inner join city id (because fetch those properties that fall under same city) --->
$sql_1 = "SELECT *, p.id AS property_id, p.name AS property_name, c.name AS city_name 
            FROM properties p
            INNER JOIN cities c ON p.city_id = c.id 
            WHERE p.id = $property_id";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo "Something went wrong!";
    return;
}

//storing propertie's records into property variable --->
$property = mysqli_fetch_assoc($result_1);
if (!$property) {
    echo "Something went wrong!";
    return;
}

//fetching testimonials using property's id ---->
$sql_2 = "SELECT * FROM testimonials WHERE property_id = $property_id";
$result_2 = mysqli_query($conn, $sql_2);
if (!$result_2) {
    echo "Something went wrong!";
    return;
}

//storing records of testimonials that fall in same property ---->
$testimonials = mysqli_fetch_all($result_2, MYSQLI_ASSOC);


//query to get amenities of a particular property --->
$sql_3 = "SELECT a.* 
            FROM amenities a
            INNER JOIN properties_amenities pa ON a.id = pa.amenity_id
            WHERE pa.property_id = $property_id";
$result_3 = mysqli_query($conn, $sql_3);
if (!$result_3) {
    echo "Something went wrong!";
    return;
}

//storing amenities into the amenities variables (of a particular property ) 
$amenities = mysqli_fetch_all($result_3, MYSQLI_ASSOC);


//to fetch interested user's properties and interested user's --->
$sql_4 = "SELECT * FROM interested_users_properties WHERE property_id = $property_id";
$result_4 = mysqli_query($conn, $sql_4);
if (!$result_4) {
    echo "Something went wrong!";
    return;
}

//storing interested user's records & interested user's count --- >
$interested_users = mysqli_fetch_all($result_4, MYSQLI_ASSOC);
$interested_users_count = mysqli_num_rows($result_4);
?>

<!-- start of html file -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $property['property_name']; ?> | PG Life</title>

    <!-- including head_links --->
    <?php
    include "includes/head_links.php";
    ?>

    <!-- including css file for property details --->
    <link href="css/property_detail.css" rel="stylesheet" />
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

            <!-- fetching city name --->
            <li class="breadcrumb-item">
                <a href="property_list.php?city=<?= $property['city_name']; ?>"><?= $property['city_name']; ?></a>
            </li>

            <!-- fetching property's name to display it in breadcrumb --->
            <li class="breadcrumb-item active" aria-current="page">
                <?= $property['property_name']; ?>
            </li>
        </ol>
    </nav>

    <!-- property images part (header : carousel part )--->
    <div id="property-images" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">

        <!-- storing image files in array and accessing it using property's id  --->
            <?php
            $property_images = glob("img/properties/" . $property['property_id'] . "/*");

            //fetching property's image  (header part : slide to next index element of the array )--->
            foreach ($property_images as $index => $property_image) {
            ?>
                <li data-target="#property-images" data-slide-to="<?= $index ?>" class="<?= $index == 0 ? "active" : ""; ?>"></li>


            <?php
            }
            ?>


        </ol>

        <!-- carousel inner part --->
        <div class="carousel-inner">
            <?php
            foreach ($property_images as $index => $property_image) {
            ?>
                <div class="carousel-item <?= $index == 0 ? "active" : ""; ?>">
                    <img class="d-block w-100" src="<?= $property_image ?>" alt="slide">
                </div>

            <?php
            }
            ?>

        </div>

        <!-- previous and next button --->
        <a class="carousel-control-prev" href="#property-images" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#property-images" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- property 's rating part for star rating icon --->
    <div class="property-summary page-container">
        <div class="row no-gutters justify-content-between">
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

            <!-- if user is interested  : interested user's id == user's id : print heart fill icon else print heart empty icon -->
            <div class="interested-container">

                <?php
                $is_interested = false;
                foreach ($interested_users as $interested_user) {
                    if ($interested_user['user_id'] == $user_id) {
                        $is_interested = true;
                    }
                }

                if ($is_interested) {
                ?>
                    <i class="is-interested-image fas fa-heart"></i>
                <?php
                } else {
                ?>
                    <i class="is-interested-image far fa-heart"></i>
                <?php
                }
                ?>

                <!-- to print the interested user's count -->
                <div class="interested-text">
                    <span class="interested-user-count"><?= $interested_users_count ?></span> interested
                </div>
            </div>
        </div>

        <!-- property's detail part --->
        <div class="detail-container">
            <div class="property-name"><?= $property['property_name'] ?></div>
            <div class="property-address"><?= $property['address'] ?></div>

            <!-- gender part --->
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

        <!-- property rent part --->
        <div class="row no-gutters">
            <div class="rent-container col-6">
                <div class="rent">₹ <?= number_format($property['rent']) ?>/-</div>
                <div class="rent-unit">per month</div>
            </div>

            <!-- to book the property --->
            <div class="button-container col-6">
                <a href="#" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>

    <!-- amenities part --->
    <div class="property-amenities">
        <div class="page-container">
            <h1>Amenities</h1>
            <div class="row justify-content-between">
                <div class="col-md-auto">

                <!-- amenity type = building part --->
                    <h5>Building</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Building") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="col-md-auto">
                    <h5>Common Area</h5>

                    <!-- amenity type = common area -->
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Common Area") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="col-md-auto">
                    <!-- amenity type = bedroom --->
                    <h5>Bedroom</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Bedroom") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>

                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="col-md-auto">
                    <!-- amenity type = washroom --->
                    <h5>Washroom</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Washroom") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- about the property : description part -->
    <div class="property-about page-container">
        <h1>About the Property</h1>
        <p><?= $property['description'] ?></p>
    </div>

    <!-- property rating part : clean rating , safety rating , food rating & total rating --->
    <div class="property-rating">
        <div class="page-container">
            <h1>Property Rating</h1>
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6">
                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fas fa-broom"></i>
                            <span class="rating-criteria-text">Cleanliness</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title="<?= $property['rating_clean'] ?>">

                        <!-- clean rating --->
                            <?php
                            $rating = $property['rating_clean'];
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
                    </div>

                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fas fa-utensils"></i>
                            <span class="rating-criteria-text">Food Quality</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title="<?= $property['rating_food'] ?>">
                        
                        <!-- food rating -->
                            <?php
                            $rating = $property['rating_food'];
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
                    </div>

                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fa fa-lock"></i>
                            <span class="rating-criteria-text">Safety</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title="<?= $property['rating_safety'] ?>">

                        <!-- safety rating -->
                            <?php
                            $rating = $property['rating_safety'];
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
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="rating-circle">

                    <!-- total rating --->
                        <?php
                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                        $total_rating = round($total_rating, 1);
                        ?>
                        <div class="total-rating"><?= $total_rating ?></div>
                        <div class="rating-circle-star-container">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- properties's testimonials --->
    <div class="property-testimonials page-container">
        <h1>What people say</h1>
        <?php
        foreach ($testimonials as $testimonial) {
        ?>
            <div class="testimonial-block">
                <div class="testimonial-image-container">
                    <img class="testimonial-img" src="img/man.png">
                </div>
                <div class="testimonial-text">
                    <i class="fa fa-quote-left" aria-hidden="true"></i>
                    <p><?= $testimonial['content'] ?></p>
                </div>
                <div class="testimonial-name">- <?= $testimonial['user_name'] ?></div>
            </div>
        <?php
        }
        ?>
    </div>

    <!-- signup modal , login modal and footer part --->
    <?php
    include "includes/signup_modal.php";
    include "includes/login_modal.php";
    include "includes/footer.php";
    ?>

<!-- javascript file to make toggle work (heart icon)--->
    <script type="text/javascript" src="js/property_detail.js"></script>

</body><!-- end of body tag -->

</html><!-- end of html file -->
