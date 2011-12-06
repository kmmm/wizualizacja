<?php

require_once 'tables/tableSymbolFamily.php';
require_once 'tables/tableDevice.php';
require_once 'tables/tableFloor.php';
require_once 'tables/tableSymbol.php';
require_once 'tables/tableElements.php';

$tableSymbolFamily = new tableSymbolFamily();
$tableSymbol = new tableSymbol();
$tableDevice = new tableDevice();
$tableFloor = new tableFloor();
$tableElements = new tableElements();

$deviceSymbolFamilyType = array("1" => "I", "0" => "O");

if (isset($_GET['id']) && $_GET['id'] != "") {

    $symbolFamily = $tableSymbolFamily->selectRecordById($_GET['id']);
    $devices = $tableDevice->selectRecordsByType($deviceSymbolFamilyType[$symbolFamily[2]]);
    if (!empty($devices)) {
        foreach ($devices as $device) {
            echo'<option value ="' . $device[0] . '">' . $device[1] . ' ' . $device[2] . '</option>';
        }
    }else{
        echo'<option value ="">Brak urządzeń</option>';
    }
}

if (isset($_GET['floorid']) && $_GET['floorid'] != "") {
    $floor = $tableFloor->selectRecordById($_GET['floorid']);
    echo $floor[2];
}

if (isset($_GET['id_element']) || isset($_GET['id_element_delete'])) {
    if (isset($_GET['id_element'])) {
        $select = "element";
        $id = $_GET['id_element'];
        $dis = null;
        $button_name = "edytuj";
        $value = "edit";
    } else {
        $select = "element_delete";
        $id = $_GET['id_element_delete'];
        $dis = "disabled=disabled";
        $button_name = "usuń";
        $value = "delete";
    }

    $floors = $tableFloor->selectAllRecords();
    $symbolsFamily = $tableSymbolFamily->selectAllRecords();
    $symbols = $tableSymbol->selectAllRecords();
    $elements = $tableElements->selectAllRecords();
    $currentElement = $tableElements->selectRecordById($id);
    $currentFloor = $tableFloor->selectRecordById($currentElement[4]);
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($currentElement[3]);
    $currentDevice = $tableDevice->selectRecordById($currentElement[2]);
    $devices = $tableDevice->selectRecordsByType($deviceSymbolFamilyType[$currentSymbolFamily[2]]);

    //var_dump($currentElement) or die();
    echo '<h4></h4><br><form action="elements.php?action=' . $value . '" method="POST">
        <table>
            <tr>
                <td>Element:</td>
                <td><select id="' . $select . '" name="' . $select . '">
                <option value=' . $currentElement[0] . '>' . $currentElement[1] . '</option>';
    foreach ($elements as $element) {
        if ($element[0] != $currentElement[0]) {
            echo '<option value ="' . $element[0] . '">' . $element[1] . '</option>';
        }
    }
    echo '</td>
             </tr>
             <tr>
                 <td>Nazwa elementu:</td>
                 <td><input type="text" id="name" name="name" value="' . $currentElement[1] . '" ' . $dis . '/></td>
             </tr>
             <tr>
                <td>Kondygnacja: </td>
                <td><select id="floor" name="floor" ' . $dis . '>
                <option value=' . $currentFloor[0] . '>' . $currentFloor[1] . '</option>';
    foreach ($floors as $floor) {
        if ($floor[0] != $currentFloor[0]) {
            echo'<option value ="' . $floor[0] . '">' . $floor[1] . '</option>';
        }
    }
    echo'</select>';
    $f = $tableFloor->selectRecordById($currentFloor[0]);
    echo'<input type="hidden" id="i" name="i" value="' . $f[2] . '"/></td>
            </tr>
            <tr>
                <td>Grupa symboli: </td>
                <td><select id="symbolfamily" name="symbolfamily" ' . $dis . '>
                <option value="' . $currentSymbolFamily[0] . '">' . $currentSymbolFamily[1] . '</option>';
    foreach ($symbolsFamily as $symbolFamily) {
        if ($symbolFamily[0] != $currentSymbolFamily[0]) {
            echo'<option value ="' . $symbolFamily[0] . '">' . $symbolFamily[1] . '</option>';
        }
    }
    echo'</select></td>
            </tr>
            <tr>
                <td>Urządzenie: </td>
                <td><select id="device" name="device" ' . $dis . '>
                <option value="' . $currentDevice[0] . '">' . $currentDevice[1] . ' ' . $currentDevice[2] . '</option>';
    foreach ($devices as $device) {
        if ($device[0] != $currentDevice[0]) {
            echo'<option value ="' . $device[0] . '">' . $device[1] . ' ' . $device[2] . '</option>';
        }
    }
    echo'</select></td>
            </tr>
            <tr>
                <td>Położenie na wizualizacji:</td>
                <td><input type="text" id="posx" name="posx" size=1 value=' . $currentElement[6] . ' ' . $dis . '>,
                    <input type="text" id="posy" name="posy" size=1 value=' . $currentElement[5] . ' ' . $dis . '>
                    <input type="button" id="position" name="position" value="wybierz pozycję" ' . $dis . '/></td>
            </tr>
            <tr><td colspan=2><button type="submit" id="send" name="send" value="' . $button_name . '">' . $button_name . '</button></td></td></tr>
        </table>
    </form>';
}
?>
