<?php

require_once('tables/tableInputs.php');
require_once('tables/tableDevice.php');

if(isset($_GET['get_id']) && isset($_GET['name'])){
    $id=$_GET['get_id'];
    $name = $_GET['name'];
    $tableInputs = new tableInputs();
    $value = $tableInputs->getValueById($id);
    if($value != 0){
        $tableInputs->setValueById($id, 0);
        echo '<input type="checkbox" div="'.$id.'" checked="yes"/>'.$name.'</input></br>';
    }
    else {
         $tableInputs->setValueById($id, 1);
        echo '<input type="checkbox" div="'.$id.'""/>'.$name.'</input>';
    } 
} else {




if (isset($_GET['id']) || isset($_GET['id_delete'])) {
    if (isset($_GET['id'])) {
        $select = "select_name";
        $id = $_GET['id'];
        $dis = null;
        $button_name = "edytuj";
        $value = "edit";
    } else {
        $select = "select_name_delete";
        $id = $_GET['id_delete'];
        $dis = "disabled=disabled";
        $button_name = "usuń";
        $value = "delete";
    }


    $tableInputs = new tableInputs();
    $inputs = $tableInputs->selectAllRecords();
    $tableDevice = new tableDevice();
    $devices = $tableDevice->selectAllRecords();
    $currentInput = $tableInputs->selectRecordById($id);
    $currentDevice = $tableDevice->selectRecordById($currentInput['id_device']);
    echo '<h4></h4><br><form action="Inputs.php?action=' . $value . '" method="POST">
                <table>
                    <tr>
                        <td>Wybierz wejście</td>
                        <td><select id="' . $select . '" name="' . $select . '">
                                <option value="' . $currentInput['id'] . '">' . $currentInput['name'] . '</option>';
    foreach ($inputs as $input) {
        if ($input['id'] != $currentInput['id']) {
            echo '<option value ="' . $input['id'] . '">' . $input['name'] . '</option>';
        }
    }
    echo '</select></td>
    </tr>
    <tr>
        <td>Nazwa: </td>
        <td><input type="text" id="name" name="name" value="' . $currentInput['name'] . '" ' . $dis . '></td>
    </tr>  

    <tr>
        <td>Port urządzenia:</td>
        <td><select id="id_device" name="id_device" ' . $dis . '>
   <option value="'.$currentDevice[0].'">'.$currentDevice[1].'</option>';
    foreach($devices as $device){
        if($device[0]!=$currentDevice[0])
        echo '<option value="'.$device[0].'">'.$device[1].'</option>';

    }

    echo '</select></td>
    </tr>
    <tr>    
    <td colspan=2>';
    echo '<input type="hidden" id="id" name="id" value="' . $currentInput['id'] . ' "/>
        <button type="submit" id="send" name="send" value="' . $button_name . '">' . $button_name . '</button></td>
    </tr>
    </table>
    </form>';
}}
?>