<?php

session_start();
require_once 'userInterface.php';
require_once 'tables/tableUser.php';
require_once 'tables/tableVisualisation.php';
require_once 'tables/tableInputs.php';

$userInterface = new userInterface();
$tableUser = new tableUser();
$content = "";
$title = "Zmiana hasła";
$headerTitle = "Zmiana hasła";
$divBackground = "";
$jquery = "";
$alert = "";
$minUserPrivleges = 1;

$intervalJQuery = "";
$documentReadyJQuery = "$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});";
$functionJQuery = "";

if ($userInterface->login()) {
    if (isset($_POST['zmień'])) {
        if (isset($_POST['old_pass']) && isset($_POST['new_pass1']) && isset($_POST['new_pass2'])) {
            if ($_POST['new_pass1'] != "" && $_POST['new_pass2'] != "" && $_POST['old_pass'] != "") {
                if ($_POST['new_pass1'] == $_POST['new_pass2']) {
                    $oldPass = md5($_POST['old_pass']);
                    $newPass = md5($_POST['new_pass1']);
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



    //INPUTY------------------------------------------------------------------------
    $tableInputs = new tableInputs();

    $inputs = $tableInputs->selectAllRecords();
    $inputForm = '<form>';
    if (!empty($inputs)) {
        foreach ($inputs as $input) {

            //var_dump($input['name']) or die();
            //$(\'.'.$input['id'].'\').load("ajaxInputs.php?get_id='.$input['id'].'&name='.$input['name'].'");
            $documentReadyJQuery.= '$(\'.' . $input['id'] . '\').change(function(){$(\'.' . $input['id'] . '\').load("ajaxInputs.php?set_id=' . $input['id'] . '");});';

            $intervalJQuery.= '$(\'.' . $input['id'] . '\').load("ajaxInputs.php?get_id=' . $input['id'] . '"); ';
            if ($tableInputs->getValueById($input['id']) == '1')
                $inputForm.='<div class="' . $input['id'] . '"><input type="checkbox" checked="yes" div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
            else
                $inputForm.='<div class="' . $input['id'] . '"><input type="checkbox"  div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
        }
    } else
        $inputForm.='Brak zdefiniowanych wejść';
    $inputForm.='</form>';
//INPUTY------------------------------------------------------------------------
    $jquery.=$functionJQuery . '$(document).ready(function(){' . $documentReadyJQuery . ' setInterval(function(){' . $intervalJQuery . '},2000); });';
    $menu = $userInterface->leftMenuIndex($inputForm);
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
