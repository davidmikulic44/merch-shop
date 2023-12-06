<?php
session_start();
error_reporting(0);
include("src/php/connection.php"); 

$sql = "SELECT * FROM item";
$result = $conn->query($sql);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="stylesheet" href="src/styles/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a88ff6df1f.js" crossorigin="anonymous"></script><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBL WEB SHOP</title>
    
</head>
<body>
    <div class="container">
        <?php include 'src/php/header.php'; ?>
        <article class="videos-container">
                <video autoplay muted class="videos" loop>
                    <source src="assets/images/videoautoplay.mp4" type="video/mp4">
                </video>
            
        </article>
        <div class="scrolling-text">
            <div class="scroller-background"></div>
            <div id="scrolling-text">
                <p>nova kolekcija 2023 /// girls 3 sbl /// SBL @ LIL DRITO IZ TVORNICE 30.9.23. /// ruda99 - ZABAVA [OUT NOW] /// SBL @ BARAKA KUTINA 8.9.23. /// mavid - fAZA [OUT NOW] /// SBL @ METRO, TVORNICA KULTURE
                    &nbsp; nova kolekcija 2023 /// girls 3 sbl /// SBL @ LIL DRITO IZ TVORNICE 30.9.23. /// ruda99 - ZABAVA [OUT NOW] /// SBL @ BARAKA KUTINA 8.9.23. /// mavid - fAZA [OUT NOW] /// SBL @ METRO, TVORNICA KULTURE&nbsp;nova kolekcija 2023 /// girls 3 sbl /// SBL @ LIL DRITO IZ TVORNICE 30.9.23. /// ruda99 - ZABAVA [OUT NOW] /// SBL @ BARAKA KUTINA 8.9.23. /// mavid - fAZA [OUT NOW] /// SBL @ METRO, TVORNICA KULTURE</p>
            </div>
        </div>
        <section class="collections-container">
    
            <h1 class="collection-title">GIRLS <i class="fa-solid fa-heart green-icon"></i> SBL COLLECTION</h1>
            <div class="collection-tshirt">

            <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $imageString=$row['images'];
                            $imageArray = explode(',', $imageString);
                            $firstImage = $imageArray[0];
                            echo '<a href="tshirt.php?ID=' . $row['ID'] . '">';
                            echo '<article class="tshirt">';
                            echo '<img class="tshirt-image" src=' . $firstImage. '>';
                            echo '<div class="price-wrapper">';
                            echo '<h1 class="tshirt-title">'.$row['name'].'</h1>';
                            echo '<div class="price"><h1>'.$row['price'].'&euro;</h1></div>';
                            echo '</div>';
                            echo '</article>';
                            echo '</a>';
                           

                        }
                    } else {
                        echo '<h1 class="tshirt-title">Nema majici</h1>';
                    }

                    $conn->close();
                    ?>

            </div>
        </section>
    
        <?php include 'src/php/footer.php'; ?>
    </div>
    <script type="module" src="spotify.js"></script>
    <script type="module" src="dynamicLoader.js"></script>
</body>
</html>