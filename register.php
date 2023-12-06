<?php
session_start();
error_reporting(0);
include("src/php/connection.php"); 
$usernameTaken=false;
if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email=$_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = mysqli_query($conn, 'select ID from user where username="' . $username . '"');
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) {
        $usernameTaken = true;
    } else {
        $query = mysqli_query($conn, 'insert into user (firstname, lastname,email, username, password, role) values 
        ("' . $firstname . '", "' . $lastname . '","' . $email . '", "' . $username . '", "' . $password . '", "customer")');

        
        $query2 = mysqli_query($conn, 'select ID from user where username="' . $username . '"');
        if ($query) {
            $ret = mysqli_fetch_array($query2);
            $_SESSION['ID'] = $ret['ID'];
            header('location: index.php');
        } else {
            echo '<script>alert("Greška kod registracije")</script>';
        }
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
        
        <form class="login-wrapper" name="register" action="#" method="post">
            <h1>Welcome, create an account!</h1>

            <div id="names">
                <div class="form-input">
                    <input type="text" class="input name" id="first-name" name="firstname" placeholder=" " required>
                    <label class="form-label" for="first-name" >first name</label>
                </div>
                <div class="form-input">
                    <input type="text" class="input name" id="last-name" placeholder=" " name="lastname" required >
                    <label class="form-label" for="last-name">last name</label>
                </div> 
            </div>
            <div class="form-input">
                <input class="input" type="username" id="username" placeholder=" " name="username" required>
                <label class="form-label" for="username">username</label>
                
            </div>

            <div class="form-input">
                <input class="input" type="e-mail" id="e-mail" placeholder=" " name="email" required>
                <label class="form-label" for="e-mail">e-mail</label>
                
            </div>
            <div class="form-input">
                <input class="input" id="password" type="password" placeholder=" " name="password" required>
                <label class="form-label" for="password">password</label>
            </div>

            <div class="form-input">
                <input class="input" id="confirm-password" type="password" placeholder=" "  required>
                <label class="form-label" for="confirm-password">confirm password</label>
            </div>
            <?php 
                if($usernameTaken){
                    echo '<p class="error-message">Username taken!</p>';
                }
            
            ?>
            <p id="password-error" class="error-message"></p>
            <div id="email-error" class="error-message"></div>

            <input type="submit" name="register" value="SIGNUP" class="login-signup-button">
            <h1 id="registerhere">Already have an account? Login <a href="login.php"><u id="register-instead-btn"><i>here</i></u></a></h1>
        </form>
    </div>
    <script src="src/js/register.js"></script>
</body>
</html>