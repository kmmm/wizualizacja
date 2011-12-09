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
   
    $userInterface->setFloor($id_floor);
    $elements = $tableVisual->selectAllRecordsByIdFloor($id_floor);
    if (!empty($elements)) {

        foreach ($elements as $element) {
            $tableVisual->prepareValueElementById($element['id']);
            $value = $tableVisual->selectValueElementById($element['id']);
            $photo = $tableVisual->selectPhotoByElementByIdAndValue($element['id'], $value);
            $is_visible = $tableVisual->selectTypeById($element['id']);
            if($is_visible==0)
                $value="";
            $content.='<div id="e'.$element['id'].'" style="position: absolute; top: '. $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $photo . '"/><h3>'.$value.'</h3></div>';

        }
    }
    }

    $menu = $userInterface->leftMenuIndex();
    $user_privileges = 0;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>