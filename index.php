<?php

/**
 * index - strona główna wizualizacji (logowanie + wizualizacja)
 */
require_once 'userInterface.php';
require_once 'tables/tableFloor.php';

$userInterface = new userInterface();
$tableFloor = new tableFloor();


if ($userInterface->login()) {

    $title = "wizualizacja";
    $jquery = null;    

    $content = '<div style="position: absolute; top: 46px; left: 584px; width: 20px; background-color: azure;">buka</div>';


    if (isset($_GET['floor'])) {
        $image = $tableFloor->selectFloorImageByFloorNumber($_GET['floor']);
        $divBackground = $image[2];
        $headerTitle = "Floor ".$_GET['floor'];
    } else {
        $image = $tableFloor->selectAllRecords();
        $divBackground = $image[0][2];
        $headerTitle = "Floor ".$image[0][1];
    }

    $content = '<img src="photo/b35de6875ea06991c09a606946aa65b33dccc918.jpg" id="1" style="position:absolute; top:108px; left:316px;"/>';
    $menu = $userInterface->leftMenuIndex();
    $user_privileges = 1;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>