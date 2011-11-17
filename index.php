<?php

/**
 * index - strona główna wizualizacji (logowanie + wizualizacja)
 */
require_once 'userInterface.php';

$userInterface = new userInterface();

$title = "wizualizacja";
$jquery = null;
$headerTitle = "Floor 0";
$content = null;
$divBackground = "images/f1.jpg";
$menu = $userInterface->leftMenuIndex();
$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground);
?>