<?php

session_start();
require_once 'userInterface.php';
require_once 'tables/tableCamera.php';

$userInterface = new userInterface();
$tableCamera = new tableCamera();
$content = "";
$title = "Kamera";
$headerTitle = "Kamera";
$divBackground = "";
$jquery = "";
$alert = "";
$minUserPrivleges = 1;

if ($userInterface->login()) {
    $camera = $tableCamera->select();
    $content = '<table class = center>
        <tr><td>Data:</td><td>'.$camera[0][2].'</td></tr>
        <tr><td colspan=2><img alt="Embedded Image" src="data:image/jpg;base64,' . $camera[0][1] . '" /></td></tr>
        </table>';
    $menu = $userInterface->leftMenuIndex();
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>