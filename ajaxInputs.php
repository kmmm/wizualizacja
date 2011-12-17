<?php

require_once('tables/tableInputs.php');
require_once('tables/tableDevice.php');
//
if(isset($_GET['get_id'])){
    $id=$_GET['get_id'];
    $tableInputs = new tableInputs();
    $checkboxes=$tableInputs->selectRecordById($id);
        if($tableInputs->getValueById($id)==1)
        echo '<input type="checkbox" div="'.$id.'" checked="yes" >'.$checkboxes['name'].'</input></br>';
        else
        echo '<input type="checkbox" div="'.$id.'">'.$checkboxes['name'].'</input></br>';
}

if(isset($_GET['set_id'])){
    $id=$_GET['set_id'];
    $tableInputs = new tableInputs();
    $value = $tableInputs->getValueById($id);
    $checkboxes=$tableInputs->selectRecordById($id);
    if($value != 0){
        $tableInputs->setValueById($id, 0);
        echo '<input type="checkbox" div="'.$id.'" >'.$checkboxes['name'].'</input></br>';
    }
    else {
         $tableInputs->setValueById($id, 1);
        echo '<input type="checkbox" div="'.$id.'" checked="yes">'.$checkboxes['name'].'</input>';
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
                                <option value="' . $currentInput['id'] . '">' . $currentInput['name'] . ' </option>';
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
   <option value="'.$currentDevice[0].'">'.$currentDevice[1].' ' .$currentDevice['2'].'</option>';
    foreach($devices as $device){
        if($device[0]!=$currentDevice[0])
        echo '<option value="'.$device[0].'">'.$device[1].' ' .$device['2'].'</option>';

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