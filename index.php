<?php

session_start();
/**
 * index - strona główna wizualizacji (logowanie + wizualizacja)
 */
require_once 'userInterface.php';
require_once 'tables/tableFloor.php';
require_once 'tables/tableVisualisation.php';

$userInterface = new userInterface();
$tableFloor = new tableFloor();
//$tableVisual = new tableVisualistation();
$tableVisual = new tableVisualisation();

if ($userInterface->login()) {

    $title = "wizualizacja";
    $jquery = '<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
   
    $("#text3").delegate("#select_port", "change", function()
    {
        var id= $("#select_port").val();
	$("#text3").load("ajaxDevice.php?id="+id);
    });
    
    $("#text3").delegate("#select_port_delete", "change", function()
    {
        var id= $("#select_port_delete").val();
	$("#text3").load("ajaxDevice.php?id_delete="+id);
    });
});
</script>';

    $content = "";
    //  $content = '<div style="position: absolute; top: 46px; left: 584px; width: 20px; background-color: azure;">buka</div>';

    $id_floor = null;

    if (isset($_GET['floor'])) {
        if (!empty($image)) {
            $image = $tableFloor->selectFloorImageByFloorNumber($_GET['floor']);
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
        $divBackground = $image[0][2];
        $headerTitle = "Floor ".$image[0][1];
        $floor = $image[0][1];    
        $id_floor = $tableFloor->selectRecord($floor, 1);
        $id_floor = $id_floor[0];

    }

    $elements = $tableVisual->selectAllRecordsByIdFloor($id_floor);
    if (!empty($elements)) {
        foreach ($elements as $element) {
            $value = $tableVisual->selectValueElementById($element['id']);
            $photo = $tableVisual->selectPhotoByElementByIdAndValue($element['id'], $value);
            $content.='<div style="position: absolute; top: ' . $element['y'] . 'px; left: ' . $element['x'] . 'px; width: 20px; background-color: azure;"><img src="' . $photo . '"/></div>';
        }
    }

    $menu = $userInterface->leftMenuIndex();
    $user_privileges = 0;
    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
}
?>