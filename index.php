<?php

session_start();
/**
 * index - strona główna wizualizacji (logowanie + wizualizacja)
 */
require_once 'userInterface.php';
require_once 'tables/tableFloor.php';
require_once 'tables/tableVisualisation.php';


$tableFloor = new tableFloor();
$tableVisual = new tableVisualisation();


$intervalJQuery = "";
$documentReadyJQuery = "$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});";
$functionJQuery = "";

$userInterface = new userInterface();
if ($userInterface->login()) {
    $title = "wizualizacja";
    $jquery = "";

    $content = "";

    $id_floor = null;
    $floor = null;

    if (isset($_GET['floor'])) {
        $image = $tableFloor->selectFloorImageByFloorNumber($_GET['floor']);
        if (!empty($image)) {
            $divBackground = $image[2];
            $headerTitle = "Piętro " . $_GET['floor'];
            $floor = $_GET['floor'];
            $id_floor = $tableFloor->selectRecord($floor, 1);
            $id_floor = $id_floor[0];
        } else {
            $divBackground = "";
            $headerTitle = "Wizualizacja";
            $id_floor = "";
            $content = "<h3><br>Brak elementów wizualizacji</h3>";
        }
    } else {
        $image = $tableFloor->selectAllRecords();
        if (!empty($image)) {
            $divBackground = $image[0][2];
            $headerTitle = "Piętro " . $image[0][1];
            $floor = $image[0][1];
            $id_floor = $tableFloor->selectRecord($floor, 1);
            $id_floor = $id_floor[0];
        } else {
            $divBackground = "";
            $headerTitle = "Wizualizacja";
            $id_floor = "";
            $content = "<h3><br>Brak elementów wizualizacji</h3>";
        }
    }



//INPUTY------------------------------------------------------------------------
    
//        $("#text3").delegate("#floor", "change", function()
//    {
//        var floorid = $("#floor").val();
//        $.get("ajaxElements.php?floorid="+floorid, function(result) {
//            $("#i").val(result);           
//        });   
//    });
       
        
        $tableInputs = new tableInputs();
        $inputs = $tableInputs->selectAllRecords();

        $inputForm = '<form action="" method="get">';

        if (!empty($inputs)) {
            foreach ($inputs as $input) {  
                 if ($tableInputs->getValueById($input['id']) == '1')
                    $inputForm.='<div id="' . $input['id'] . '"><input type="checkbox" checked="yes" div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
                else
                    $inputForm.='<div id="' . $input['id'] . '"><input type="checkbox"  div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
                
                
                $documentReadyJQuery.='$("#'.$input['id'].'").change(function(){
                        $("#' . $input['id'] . '").load("ajaxInputs.php?set_id=' . $input['id'] . '");
                   });';
                $intervalJQuery.='$("#' . $input['id'] . '").load("ajaxInputs.php?get_id=' . $input['id'] . '"); ';               
            }
            
        } else
            $inputForm.='Brak zdefiniowanych wejść';
//INPUTY------------------------------------------------------------------------


    if ($id_floor != "") {
        $userInterface->setFloor($id_floor);
        $elements = $tableVisual->selectDefaultRecordsByIdFloor($id_floor);
        if (!empty($elements)) {


            foreach ($elements as $element) {
                
                switch($element['is_visible']){
                    case 0:
                        $content.='<div id="e' . $element['id'] . '" style="position: absolute; top: ' . $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $element['link_photo'] . '"/></div>';
                    if ($element['value'] == '1')
                        $value = '0';
                    else
                        $value = '1';
                    $documentReadyJQuery.='$(\'#e' . $element['id'] . '\').click(function(){$("#e' . $element['id'] . '").load("ajaxVisual.php?id=' . $element['id'] . '");});';
                         break;
                     
                    case 1:
                        $content.='<div id="e' . $element['id'] . '" style="position: absolute; top: ' . $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $element['link_photo'] . '"/><h3>' . $element['value'] . '</h3></div>';
                        break;
                    
                    case 2:
                        
                        break;
                    
                }
                $intervalJQuery.='$("#e' . $element['id'] . '").load("ajaxVisual.php?get=' . $element['id'] .'");';
                
//                if ($element['is_visible'] == '1')
//                    $content.='<div id="e' . $element['id'] . '" style="position: absolute; top: ' . $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $element['link_photo'] . '"/><h3>' . $element['value'] . '</h3></div>';
//                else {
//                    $content.='<div id="e' . $element['id'] . '" style="position: absolute; top: ' . $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $element['link_photo'] . '"/></div>';
//                    if ($element['value'] == '1')
//                        $value = '0';
//                    else
//                        $value = '1';
//                    
//                    $documentReadyJQuery.='$(\'#e' . $element['id'] . '\').click(function(){$("#e' . $element['id'] . '").load("ajaxVisual.php?id=' . $element['id'] . '");});';
//                }
//                $intervalJQuery.='$("#e' . $element['id'] . '").load("ajaxVisual.php?get=' . $element['id'] .'");';
//            
//            
                }
        }
    }

    $menu = $userInterface->leftMenuIndex($inputForm);
    $jquery.=$functionJQuery . '$(document).ready(function(){' . $documentReadyJQuery . ' setInterval(function(){' . $intervalJQuery . '},2000); });';
    $user_privileges = 0;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>
