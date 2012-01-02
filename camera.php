<?php

session_start();
require_once 'userInterface.php';
require_once 'tables/tableCamera.php';
require_once 'tables/tableVisualisation.php';

$userInterface = new userInterface();
$tableCamera = new tableCamera();
$content = "";
$title = "Kamera";
$headerTitle = "Kamera";
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

    $camera = $tableCamera->select();
    if (!empty($camera)) {
        $content = '<table class = center>
        <tr><td>Data:</td><td>' . $camera[0][2] . '</td></tr>
        <tr><td colspan=2><img alt="Embedded Image" src="data:image/jpg;base64,' . $camera[0][1] . '" /></td></tr>
        </table>';
    } else {
        $content = '<h3><br>Brak zdjęć w bazie</h3>';
    }

    //INPUTY------------------------------------------------------------------------
    $tableInputs = new tableInputs();

    $inputs = $tableInputs->selectAllRecords();
    $inputForm = '<form>';
    if (!empty($inputs)) {
        foreach ($inputs as $input) {
            //var_dump($input['name']) or die();
            //$(\'.'.$input['id'].'\').load("ajaxInputs.php?get_id='.$input['id'].'&name='.$input['name'].'");
            $documentReadyJQuery.='$(\'.' . $input['id'] . '\').change(function(){$(\'.' . $input['id'] . '\').load("ajaxInputs.php?set_id=' . $input['id'] . '");});';

            $intervalJQuery.='$(\'.' . $input['id'] . '\').load("ajaxInputs.php?get_id=' . $input['id'] . '"); ';
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
