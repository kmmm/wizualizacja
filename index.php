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
//podajesz poziom uprawnien wymaganych do wyswietlenia strony - im wyzsza tym wieksze uprawnienia,
// 0 to oczywiscie gosc, mysle ze admina mozna zrobic z okolo 100 
//albo w interfejsie klasy albo przez show mozna to podawac...
if ($_SESSION['privileges'] == 0) {
    $title = "wizualizacja";
    $jquery = null;
    $headerTitle = "Logowanie";
    $content = null;
    $content = '<h3>Proszę się zalogować - podając swoje dane</h3></br>
    <form action="loginUser.php" method="post"><div>
    <h4>Login:</h4>
    <input type="text" name="login">
    <h4>Hasło:</h4>
    <input type="password" name="haslo"></br>
    <input type="submit" name="wyślij">
    </div></form>';
    // tutaj dodac formularz od logowania - i przejechac na loginUser.php ?

    $menu = "";
    $divBackground = "";
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
} else {
    $user_privileges = 50;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}

//
?>