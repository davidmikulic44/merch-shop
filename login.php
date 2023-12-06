<?php
session_start();
error_reporting(0);
include("src/php/connection.php"); 
$loginFailed = false;

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = mysqli_query($conn, "select ID, role from user where username='$username' && password='$password' ");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) {
        $_SESSION['ID'] = $ret['ID'];
        header('location: index.php');

    } else { 
        $loginFailed = true;
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
    <title>LOGIN</title>
    
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
                        header('location: index.php');
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
        
        <form class="login-wrapper" method="post" name="login" action="#">
            <h1>Welcome back!</h1>
            

            <div class="form-input">
                <input class="input" type="username" id="username" name="username" placeholder=" " required>
                <label class="form-label" for="username">username</label>
                
            </div>
            <div class="form-input">
                <input class="input" id="password" type="password" name="password" placeholder=" " required>
                <label class="form-label" for="password">password</label>
            </div>
            <?php 
            if($loginFailed){
                echo '<div class="form-input">';
                echo '<p class="error-message">Login failed.</p>';
                echo '</div>';
            }
            
            ?>
            <input type="submit" value="LOGIN" name="login" class="login-signup-button">
            <h1 id="registerhere">Don't have an account yet? Register <a href="register.php"><u id="register-instead-btn"><i>here</i></u></a></h1>
        </form>
    </div>
    <script src="circlemovement.js"></script>
</body>
</html>