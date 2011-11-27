<?php

require_once('tables/tableFloor.php');
$floors = new tableFloor();

if(isset($_GET['floor'])){
    $img = $floors->selectFloorImageByFloorNumber($_GET['floor']);
    //echo '<img src="' . $img[2] . '" align=center valign=middle>';
    echo '<style=\'background-image: url("' . $img[2] . '")\'>';
}



?>
