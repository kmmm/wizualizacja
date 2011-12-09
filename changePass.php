<?php
session_start();
require_once 'userInterface.php';
require_once 'tables/tableUser.php';
require_once 'tables/tableVisualisation.php';

$userInterface = new userInterface();
$tableUser = new tableUser();
$content = "";
$title = "Zmiana hasła";
$headerTitle = "Zmiana hasła";
$divBackground = "";
$jquery = "";
$alert="";
$minUserPrivleges = 1;
if ($userInterface->login()) {
    if (isset($_POST['zmień'])) {        
        if (isset($_POST['old_pass']) && isset($_POST['new_pass1']) && isset($_POST['new_pass2'])) {            
            if ($_POST['new_pass1'] != "" && $_POST['new_pass2'] != "" && $_POST['old_pass'] != "") {                
                if ($_POST['new_pass1'] == $_POST['new_pass2']) {
                    $oldPass=md5($_POST['old_pass']);
                    $newPass=md5($_POST['new_pass1']);
                    //$alert = $tableUser->updatePass($_SESSION['login'], $_POST['old_pass'], $_POST['new_pass1']);
                    $alert = $tableUser->updatePass($_SESSION['login'], $oldPass, $newPass);
                } else {
                    $alert = '<h4>Nowe hasła różnią się.</h4>';
                }
            } else
                $alert = '<h4>Proszę uzupełnić wszystkie pola!</h4>';
        }
    }
    $form = '<form action="changePass.php" method="post">
        <table>
        <tr><td>Stare hasło:</td>
        <td><input type="password" name="old_pass"></td></tr>
        <tr><td>Nowe hasło:</td>
        <td><input type="password" name="new_pass1" id="new_pass1"></td></tr>
        <tr><td>Powtórz nowe hasło:</td>
        <td><input type="password" name="new_pass2" id="new_pass2"></td></tr>
        <tr><td colspan=2><button type="submit" id="zmień" name="zmień" value="zmień">zmień</button></td></tr>
        </table></form>';
    $content = $userInterface->adminPanelFormFrame("", $form, 'Zmień hasło', $alert);    
    $menu = $userInterface->leftMenuIndex();
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}

?>