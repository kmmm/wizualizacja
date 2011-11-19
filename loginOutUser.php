<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'userInterface.php';

$userInterface = new userInterface();

$title = "wizualizacja";
$jquery = '<script>
		function init(){
				setTimeout(\'document.location="index.php"\', 2000);
			}
		window.onload=init;
	</script>';
$headerTitle = "Wylogowanie";
$content = "<h3>Wylogowano!</h3>";

$user_privileges=0;

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
$userInterface->logout();
?>
