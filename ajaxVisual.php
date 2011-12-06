<?php
require_once('connectDb.php');
$connectDB = new connectDb();

require_once 'tables/tableVisualisation.php';

$tableVisual = new tableVisualisation();

$content = "";
if(isset($_GET['all']) && $_GET['all']=='1' && isset($_GET['floor'])){
    $elements = $tableVisual->selectAllRecordsByIdFloor($_GET['floor']);
    foreach($elements as $element){
        $content.=$element['id'].",";
    }
}

if(isset($_GET['get']) && isset($_GET['floor'])){
    $tableVisual->prepareValueElementById($_GET['get']);
    $value = $tableVisual->selectValueElementById($_GET['get']);
    $photo = $tableVisual->selectPhotoByElementByIdAndValue($_GET['get'], $value);

                $is_visible = $tableVisual->selectTypeById($_GET['get']);
            if($is_visible==0)
        echo '<img src="' . $photo . '"/>';
            else
                echo '<img src="' . $photo . '"/><h3>'.$value.'</h3>';
   
}
echo $content;

?>
