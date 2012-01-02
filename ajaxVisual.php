<?php
session_start();
require_once('connectDb.php');
$connectDB = new connectDb();

require_once 'tables/tableVisualisation.php';

$tableVisual = new tableVisualisation();

$content = "";

if(isset($_GET['id'])) {
    if(isset($_SESSION['privileges']))
        if($_SESSION['privileges']>=1){
            $element = $tableVisual->selectElementById($_GET['id']);
            $value = $element['value'];
        if($value == '1')
            $value = '0';
        else $value = '1';
     $tableVisual->setValueById($_GET['id'], $value);}
}
if(isset($_GET['get'])){
    $element = $tableVisual->selectElementById($_GET['get']);
    $tableVisual->prepareValueElementById($_GET['get']);
    if($element==null)
        $element = $tableVisual->selectDefaultRecordById ($_GET['get']);
            if($element['is_visible']==0)
        echo '<img src="' . $element['link_photo'] . '"/>';
            else
                echo '<img src="' . $element['link_photo'] . '"/><h3>'.$element['value'].'</h3>';
   
}
echo $content;

?>
