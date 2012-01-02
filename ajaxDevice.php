<?php

require_once('tables/tableDevice.php');
$types= array("I"=>"Input", "O"=>"Output");//, "F"=>"Flag", "R"=>"Register" );

if (isset($_GET['id']) || isset($_GET['id_delete'])) {
    if (isset($_GET['id'])) {
        $select = "select_port";
        $id = $_GET['id'];
        $dis = null;
        $button_name = "edytuj";
        $value = "edit";
    } else {
        $select = "select_port_delete";
        $id = $_GET['id_delete'];
        $dis = "disabled=disabled";
        $button_name = "usuń";
        $value = "delete";
    }


    $tableDevice = new tableDevice();
    $devices = $tableDevice->selectAllRecords();
    $currentDevice = $tableDevice->selectRecordById($id);
    echo '<h4></h4><br><form action="device?action=' . $value . '" method="POST">
                <table>
                    <tr>
                        <td>Port:</td>
                        <td><select id="' . $select . '" name="' . $select . '">
                                <option value="' . $currentDevice[0] . '">' . $currentDevice[1] . ' '.$currentDevice[2].'</option>';
    foreach ($devices as $device) {
        if ($device[0] != $currentDevice[0]) {
            echo '<option value ="' . $device[0] . '">' . $device[1] . ' '.$device[2].'</option>';
        }
    }
    echo '</select></td>
    </tr>
    <tr>
        <td>Numer portu: </td>
        <td><input type="text" id="port" name="port" value="' . $currentDevice[1] . '" ' . $dis . '></td>
    </tr>
    <tr>
        <td>Typ:</td>
        <td><select id="type" name="type" ' . $dis . '>
            <option value="'.$currentDevice[2].'">'.$types["$currentDevice[2]"].'</option>';
    foreach ($types as $type=>$typeName){
        if($type!=$currentDevice[2]){
            echo '<option value="'.$type.'">'.$typeName.'</option>';
        }
    }
            
    echo '</select></td>
    </tr>
    <tr>    
    <td colspan=2>';
    echo '<input type="hidden" id="id" name="id" value="' . $currentDevice[0] .'"/>
        <button type="submit" id="send" name="send" value="' . $button_name . '">' . $button_name . '</button></td>
    </tr>
    </table>
    </form>';
}
?>