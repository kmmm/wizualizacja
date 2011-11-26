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
    $content = null;

    if (isset($_GET['floor'])) {
        $image = $tableFloor->selectFloorImageByFloorNumber($_GET['floor']);
        $divBackground = $image[2];
        $headerTitle = "Floor ".$_GET['floor'];
    } else {
        $image = $tableFloor->selectAllRecords();
        $divBackground = $image[0][2];
        $headerTitle = "Floor ".$image[0][1];
    }

    $menu = $userInterface->leftMenuIndex();
    $user_privileges = 1;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>