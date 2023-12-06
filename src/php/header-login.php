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