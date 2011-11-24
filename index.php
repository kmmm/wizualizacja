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
    $headerTitle = "Floor 0";
    $content = null;

    if (isset($_GET['floor'])) {
        $image = $tableFloor->selectFloorImageByFloorNumber($_GET['floor']);
        //$divBackground = "photo/floor8d4467a4e89999964ae3f90ce36b1ef142680d14.jpg";
        $divBackground = $image[2];
    } else {
        $image = $tableFloor->selectAllRecords();
        $divBackground = $image[0][2];
    }

    $menu = $userInterface->leftMenuIndex();
    $user_privileges = 1;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>