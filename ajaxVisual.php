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
            $value = $tableVisual->getValueById ($_GET['id']);
        if($value == '1')
            $value = '0';
        else $value = '1';
     $tableVisual->setValueById($_GET['id'], $value);}
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
