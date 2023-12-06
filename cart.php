<?php
session_start();
error_reporting(0);
include("src/php/connection.php"); 

$size = $_POST['size'];

$cost = 0;

$query = mysqli_query($conn, 'SELECT username FROM user WHERE ID = "'.$user_id.'"');

if(strlen($_SESSION['ID']) != 0){
    $loggedIn = true;
    $user_id = $_SESSION['ID'];
}
else {
    $loggedIn = false;
}

if(isset($_POST['pay-now'])){
    $query = mysqli_query($conn, 'update cart set is_paid = 1 where user_ID = '.$user_id.' and is_paid = 0');
}

if(isset($_POST['remove-item'])){
    $cart_item_id = $_POST['cart-item-id'];
    $price = $_POST['cart-item-price'];

    $query = mysqli_query($conn, 'delete from cart_item where ID = '.$cart_item_id);

    $query = mysqli_query($conn, 'select cost from cart where user_ID = '.$user_id.' and is_paid = 0');
    $ret = mysqli_fetch_array($query);
    $new_cost = $ret[0];
    $new_cost = $new_cost - $price;
    
    if($new_cost!=0){
        $query = mysqli_query($conn, 'update cart set cost = '.$new_cost.' where user_ID = '.$user_id.' and is_paid = 0');
    }
    else {
        $query = mysqli_query($conn, 'delete from cart where user_ID = '.$user_id.' and is_paid = 0');
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="stylesheet" href="src/styles/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a88ff6df1f.js" crossorigin="anonymous"></script><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja košarica</title>
    
</head>
<body>
    <div class="container">
    <header>
            <h1 class="header-title">
                <a href="index.php">
                GIRLS <i class="fa-solid fa-heart green-icon"></i> SBL
                </a>
            </h1>
            <div class="header-user-actions">
                <div class="cart">
                    <a href="cart.php" class="header-action-btn"><i class="fa-solid fa-cart-shopping"></i></a>
                </div>

                <?php
                    $user_id = $_SESSION['ID'];
                    $query = mysqli_query($conn, 'SELECT username FROM user WHERE ID = "'.$user_id.'"');
                    $row = mysqli_fetch_array($query);

                    if(strlen($_SESSION['ID']) != 0){
                        echo '<div class="login">';
                        echo $row['username'];
                        echo '</div>';
                        echo '<div class="login">';
                        echo '<a class="header-action-btn" href="src/php/logout.php">LOGOUT</a>';
                        echo '</div>';
                    }
                    else {
                        echo '<div class="login">';
                        echo '<a href="login.php" class="header-action-btn">LOGIN</a>';
                        echo '</div>';
                        echo '<div class="register">';
                        echo '<a href="register.php" class="header-action-btn">REGISTER</a>';
                        echo '</div>';
                    }
                ?>
                
            </div>
        </header>
        <div class="cart-container">
            <div class="cart-wrapper">

                <?php
                    if($loggedIn){
                        $query = mysqli_query($conn, 'select ID, cost from cart where user_ID = '. $user_id . ' and is_paid = 0');
                        if($query){
                            $ret = mysqli_fetch_array($query);
                            if($ret){
                                $cart_id = $ret[0];
                                $cost = $ret[1];
                                $query = 'select * from cart_item where cart_id = '. $cart_id;
                                $result = $conn->query($query);

                                if($result->num_rows > 0){
                                    while ($row = $result->fetch_assoc()) {
                                        $cart_item_id = $row['ID'];
                                        $size = $row['size'];
                                        $item_id = $row['item_id'];
        
                                        $query = mysqli_query($conn, 'select * from item where ID = '. $item_id);
                                        $ret = mysqli_fetch_array($query);
                                        $name = $ret[1];
                                        $price = $ret[2];
                                        $imageString=$ret[3];
                                        $imageArray = explode(',', $imageString);
                                        $firstImage = $imageArray[0];
                                        
                                        echo '<article class="item">';
                                        echo '<div class="item-container">';
                                        echo '<img src="'. $firstImage .'" class="cart-item-img" alt="">';
                                        echo '<div class="item-info">';
                                        echo '<h1 class="item-name">'. $name .'</h1>';
                                        echo '<h1 class="item-size">Size '. strtoupper($size) .'</h1>';
                                        echo '<h1 class="price">'. $price .'&euro;</h1>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<form name="remove-item-form" class="cart-form" method="post">';
                                        echo '<input type="hidden" name="cart-item-price" value="'.$price.'"/>';
                                        echo '<input type="hidden" name="cart-item-id" value="'.$cart_item_id.'"/>';
                                        echo '<input type="submit" name="remove-item" value="&#10005;" id="close-btn-item"/>';
                                        echo '</form>';
                                        echo '</article>';
                                    } 
                                }
                            }
                        }
                        else {
                            $cost = 0;
                        }
                        
                        
                        
                    }
                    else {
                        echo '<article class="item">';
                        echo '<div class="item-container">';
                        echo '<div class="item-info">';
                        echo '<h1 class="item-size">Log in to acces the cart.</h1>';
                        echo '</div>';
                        echo '</article>';
                    }
                
                
                ?>
            
            <?php 
                if($loggedIn) {
                    
                    if($cost != 0){
                        echo '<div class="cart-payment">';
                        echo '<div class="cart-price-wrapper">';
                        echo '<h1 class="item-size">Total cost:</h1>';
                        echo '<h1 class="price" id="price-total">'.$cost.'&euro;</h1>';
                        echo '</div>';
                        echo '<form name="pay-now-form" class="cart-form" method="post">';
                        echo '<input type="submit" value="PAY NOW" name="pay-now" class="add-to-cart-btn">';
                        echo '</form>';
                        echo '</div>';
                    }
                    else {
                        echo '<article class="item">';
                        echo '<div class="item-container">';
                        echo '<div class="item-info">';
                        echo '<h1 class="item-size">Your cart is empty.</h1>';
                        echo '</div>';
                        echo '</article>';
                    }
                }
            
            ?>
            </div>
        </div>
        <footer>
            <div class="footer-links">
                <a href="https://instagram.com/sbl022">
                    <img src="assets/images/instagram.png" class="image-link" alt="instagram link">
                </a>
                <a href="https://www.youtube.com/@SBLici">
                    <img src="assets/images/youtube.png" class="image-link" alt="youtube link">
                </a>
            </div>

            <h1 class="footer-text">SBL ® 2023</h1>

            <div class="spotify-link">
                <img class="spotify-image" src="assets/images/spotify.jpg" alt="spotify image">
                <div class="spotify-text">
                    <h1 class="spotify-title">Loading...</h1>
                    <h1 class="spotify-description"></h1>
                </div>
            </div>

        </footer>
    </div>
    <script type="module" src="spotify.js"></script>
</body>
</html>