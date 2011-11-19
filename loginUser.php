<?php

require_once 'userInterface.php';
require_once 'connectDb.php';

$userInterface = new userInterface();

$title = "wizualizacja";
$jquery = null;
$headerTitle = "Logowanie";
$content = null;
if(isset($_POST['login']) && isset($_POST['haslo']) && $_SESSION['privileges']==0){
    //pobieramy z bazy... i sprawdzamy...
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $connectionWithDb = new connectDb();
    $query = 'SELECT user_type.type AS TYPE FROM user_type WHERE id = (SELECT id_user_type FROM user WHERE login = "'.$login.'" AND password = "'.$haslo.'")';
     $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        if (empty($row)) {
            $content="<h3>Niestety, błąd logowania! - popraw swoje dane</h3>";
        }else{
            $content='Brawo, wiatmy '.$login.'!';
            $_SESSION['privileges']=$row['0'];
            
        }
} else $content = '<h2>Nie można zalogować się dwa razy!</h2>';

//$divBackground = "images/f1.jpg";
//$menu = $userInterface->leftMenuIndex();
//podajesz poziom uprawnien wymaganych do wyswietlenia strony - im wyzsza tym wieksze uprawnienia,
// 0 to oczywiscie gosc, mysle ze admina mozna zrobic z okolo 100 
//albo w interfejsie klasy albo przez show mozna to podawac...
$user_privileges=0;

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
?>
