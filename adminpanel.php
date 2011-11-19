<?php

/**
 * adminpanel - panel administratora
 */

require_once 'userInterface.php';

$userInterface = new userInterface();

$title = "Panel administracyjny";
$jquery = null;
$headerTitle = "Panel administracyjny";
$content = 'FORMULARZE';
$divBackground = null;

$symbolGroups = '<li><a href="index.php">Dodaj grupę symboli</a></li>
           <li><a href="index.php">Zarządzaj grupą symboli</a></li>';

$symbols = '<li><a href="index.php">Dodaj symbol</a></li>
           <li><a href="index.php">Zarządzaj symbolami</a></li>';

$devices = '<li><a href="index.php">Dodaj urządzenie</a></li>
           <li><a href="index.php">Zarządzaj urządzeniami</a></li>';

$inputs = '<li><a href="index.php">Dodaj wejście</a></li>
           <li><a href="index.php">Zarządzaj wejściami</a></li>';

$floors = '<li><a href="index.php">Dodaj piętro</a></li>
           <li><a href="index.php">Zarządzaj piętrami</a></li>';

$elements = '<li><a href="index.php">Dodaj element wizualizacji</a></li>
           <li><a href="index.php">Zarządzaj elementami wizualizacji</a></li>';

$users = '<li><a href="index.php">Dodaj użytkwonika</a></li>
           <li><a href="index.php">Zarządzaj użytkownikami</a></li>';

$logs = '<li><a href="index.php">Zarządzaj logami</a></li>
          <li><a href="index.php">Dodaj logi</a></li>';


$menu = array('Grupy symboli' => $symbolGroups, 'Symbole' => $symbols, 'Urządzenia' => $devices, 'Wejścia'=> $inputs, 
        'Kondygnacje' => $floors, 'Elementy wizualizacji' => $elements, 'Użytkownicy' => $users, 'Logi' => $logs );

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, 100);
?>
