<?php
session_start();
error_reporting(0);
include("src/php/connection.php");

$size = $_POST['size'];
$user_id = $_SESSION['ID'];

$query = mysqli_query($conn, 'SELECT username FROM user WHERE ID = "'.$user_id.'"');

if(strlen($_SESSION['ID']) != 0){
    $loggedIn = true;
}
else {
    $loggedIn = false;
}

if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];

    session_start();
    error_reporting(0);

    $sql = "SELECT * FROM item WHERE ID = $ID";
    $result = $conn->query($sql);
    if($result->num_rows) {
        $row = $result->fetch_assoc();
        $pageTitle = $row['name_raw'];
        $price = $row['price'];
        $item_id = $ID;
    }
    else {
        header('location: index.php');
    }
}
if(isset($_POST['add-to-cart'])) {
    $size = $_POST['size'];
    $query = mysqli_query($conn, 'select * from cart where user_ID = '. $user_id . ' and is_paid = 0');
    $ret = mysqli_fetch_array($query);
    if($ret == 0){
        $query = mysqli_query($conn, 'insert into cart (user_ID, cost) values ("' . $user_id . '","'. $price .'")');
        $query = mysqli_query($conn, 'select ID from cart where user_ID = '. $user_id . ' and is_paid = 0');
        $ret = mysqli_fetch_array($query);
        $cart_id = $ret[0];


        $query = mysqli_query($conn, 'insert into cart_item (cart_id, item_id, size) values
        ("' . $cart_id . '","'. $item_id .'", "'. $size .'")');
        
        
    
    }
    else {
        $query = mysqli_query($conn, 'select cost from cart where user_ID = '. $user_id . ' and is_paid = 0');
        $ret = mysqli_fetch_array($query);
        $cost = $ret[0] + $price;

        $query = mysqli_query($conn, 'update cart set cost = '. $cost .' where user_ID = '. $user_id . ' and is_paid = 0');
        $query = mysqli_query($conn, 'select ID from cart where user_ID = '. $user_id . ' and is_paid = 0');
        $ret = mysqli_fetch_array($query);
        $cart_id = $ret[0];
        $query = mysqli_query($conn, 'insert into cart_item (cart_id, item_id, size) values
        ("' . $cart_id . '","'. $item_id .'", "'. $size .'")');
        
    }
    
}



    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a88ff6df1f.js" crossorigin="anonymous"></script><meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>

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

        <div class="tshirt-container">
            <div class="tshirt-images">
                <?php 
                    if (isset($_GET['ID'])) {
                        $ID = $_GET['ID'];
                        $sql = "SELECT * FROM item WHERE ID = $ID";
                        $result = $conn->query($sql);
                            if($result->num_rows) {
                                $row = $result->fetch_assoc();
                                $imageString=$row['images'];
                                $imageArray = explode(',', $imageString);
                                echo '<img src="'.$imageArray[0].'" onclick="openModal()" alt="" id="front-image">';
                                echo '<div id="bottom-images">';
                                echo '<img src="'.$imageArray[0].'" alt="" class="bottom-image" onclick="replaceImage(this)">';
                                echo '<img src="'.$imageArray[1].'" alt="" class="bottom-image" onclick="replaceImage(this)">';
                                echo '<img src="'.$imageArray[2].'" alt="" class="bottom-image" onclick="replaceImage(this)">';
                                echo '</div>';


                            }
                    }
                    
                    
                ?>  
            </div>
            <div id="overlay" class="overlay" onclick="closeModal()">
                <div class="modal">
                    <span class="close-btn" onclick="closeModal()"><i class="fas fa-window-close"></i></span>
                    <img src="" alt="Large Image" id="modal-image">
                </div>
            </div>
            <div class="tshirt-info">
                <div class="name-price-container">
                    <?php
                    echo '<h1 id="tshirt-name">'.$row['name'].'</h1>';
                    echo '<h1 id="price">'.$row['price'].'&euro;</h1>';
                    ?>
                </div>



                <ul class="tshirt-description">
                    <li>185g/m2</li>
                    <li>100% pamuk</li>
                    <li>Kratki rukav</li>
                    <li>Zaobljeni izrez</li>
                    <li>Cjevasti izrez</li>
                    <li>Fitted</li>
                    <li>Sitotisak</li>
                </ul>

                <form class="form-input" name="add-to-cart-form" method="post" action="#">
                    <div class="sizes">
                        <input type="radio" name="size" id="s" value="s" required>
                        <label for="s"><span>S</span></label>
                        
                        <input type="radio" name="size" id="m" value="m">
                        <label for="m"><span>M</span></label>
                        
                        <input type="radio" name="size" id="l" value="l" checked>
                        <label for="l"><span>L</span></label>
                        
                        <input type="radio" name="size" id="xl" value="xl">
                        <label for="xl"><span>XL</span></label>
                    </div>

                    <?php 
                    if(!$loggedIn){
                        echo '<p>Log in to add to cart.</p>';
                    }
                    else {
                        echo '<input type="submit" name="add-to-cart" value="DODAJ U KOŠARICU" class="add-to-cart-btn">';
                    }
                    ?>
                </form>
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
    <script src="tshirt.js"></script>
</body>
</html>