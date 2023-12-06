<?php
function getImage($item_id, $index, $result) {
    $row = $result->fetch_assoc();
    $imageString = $row['images'];
    $imageArray = explode(',', $imageString);

    return $imageArray[$index];
}
?>
