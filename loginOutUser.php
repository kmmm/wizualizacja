<?php
session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'userInterface.php';

$userInterface = new userInterface();

$title = "Wylogowywanie";
$jquery = 'function init(){
				setTimeout(\'document.location="index.php"\', 2000);
			}
		window.onload=init;';
$headerTitle = "Wylogowywanie";
$content = "<h4><br>Zostałeś poprawnie wylogowany!</h4><br>Za chwilę nastąpi przekierowanie na <a href=index.php>stronę główną</a>.";

$user_privileges = 0;
$menu = null;
$divBackground = null;
$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
$userInterface->logout();
?>
