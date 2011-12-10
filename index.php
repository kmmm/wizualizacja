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
 

$intervalJQuery ="";
$documentReadyJQuery ="";
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
            $headerTitle = "Floor " . $image[0][1];
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


        
    
    if($id_floor!=""){


//$functionJQuery ='function getData(data) {   
//    var i=0;
//    var id=-1;
//    var photo=-1;
//    var start = -1;
//    
//        while(data.length>i)
//        {
//        if(data[i]!="," && start == -1){
//            start = i;
//        }
//        if(data[i]==","){
//            id=data.slice(start,i);
//
//            start=-1;
//	}
//	i++;
//        }        
//    
//}
//        function getAll(){
//         $.get("ajaxVisual.php?all=1&floor='.$id_floor.'", getData);
//}';

//INPUTY------------------------------------------------------------------------
        $tableInputs = new tableInputs();
         
        $inputs = $tableInputs->selectAllRecords();
        $inputForm = '';
                if (!empty($inputs)) {
            foreach ($inputs as $input) {
                //var_dump($input['name']) or die();
                //$(\'.'.$input['id'].'\').load("ajaxInputs.php?get_id='.$input['id'].'&name='.$input['name'].'");
                $documentReadyJQuery.='$(\'.' . $input['id'] . '\').change(function(){
                            $(\'.' . $input['id'] . '\').load("ajaxInputs.php?set_id=' . $input['id'] . '");  
                            });';
                
                $intervalJQuery.='$(\'.' . $input['id'] . '\').load("ajaxInputs.php?get_id=' . $input['id'] . '"); ';
                if ($tableInputs->getValueById($input['id']) == '1')
                    $inputForm.='<div class="' . $input['id'] . '"><input type="checkbox" checked="yes" div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
                else
                    $inputForm.='<div class="' . $input['id'] . '"><input type="checkbox"  div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
            }
        } else
            $inputForm.='Brak zdefiniowanych wejść';
//INPUTY------------------------------------------------------------------------


   
    $userInterface->setFloor($id_floor);
    $elements = $tableVisual->selectDefaultRecordsByIdFloor($id_floor);
    if (!empty($elements)) {

        
        foreach ($elements as $element) {

            if($element['is_visible']=='1')
            $content.='<div id="e'.$element['id'].'" style="position: absolute; top: '. $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $element['link_photo'] . '"/><h3>'.$element['value'].'</h3></div>';
            else{
            $content.='<div id="e'.$element['id'].'" style="position: absolute; top: '. $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $element['link_photo'] . '"/></div>';
            if($element['value']=='1')
                $value='0';
            else
                $value='1';
            $documentReadyJQuery.='$(\'#e' . $element['id'] . '\').click(function(){
                                $("#e'.$element['id'].'").load("ajaxVisual.php?id='.$element['id'].'");  

                                });';
            }
                        $intervalJQuery.='
                            $("#e'.$element['id'].'").load("ajaxVisual.php?get='.$element['id'].'&floor='.$id_floor.'");';

        }
    }
    }

    $menu = $userInterface->leftMenuIndex($inputForm);
    $jquery.=$functionJQuery.'$(document).ready(function(){'.$documentReadyJQuery.' setInterval(function(){'.$intervalJQuery.'},2000); });';
    $user_privileges = 0;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>