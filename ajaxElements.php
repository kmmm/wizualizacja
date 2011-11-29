<?php

require_once 'tables/tableSymbolFamily.php';
require_once 'tables/tableDevice.php';
require_once 'tables/tableFloor.php';

$tableSymbolFamily = new tableSymbolFamily();
$tableDevice = new tableDevice();
$tableFloors = new tableFloor();

$deviceSymbolFamilyType = array("1" => "I", "0" => "O");

if (isset($_GET['id']) && $_GET['id'] != "") {
    $symbolFamily = $tableSymbolFamily->selectRecordById($_GET['id']);
    $devices = $tableDevice->selectRecordsByType($deviceSymbolFamilyType[$symbolFamily[2]]);
    foreach ($devices as $device) {
        echo'<option value ="' . $device[0] . '">' . $device[1] . ' ' . $device[2] . '</option>';
    }
}

if (isset($_GET['floorid']) && $_GET['floorid'] != "") {
    $floor=$tableFloors->selectRecordById($_GET['floorid']);
    echo $floor[2];
}else{
    echo 'buu';
}
?>
