<?php

require_once('tables/tableSymbolFamily.php');

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


    $tableSymbolFamily = new tableSymbolFamily();
    $symbolFamily = $tableSymbolFamily->selectAllRecords();
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($id);
    echo '<h4></h4><br><form action="symbol_family.php?action=' . $value . '" method="POST">
                <table>
                    <tr>
                        <td>Grupa symboli:</td>
                        <td><select id="' . $select . '" name="' . $select . '">
                                <option value="' . $currentSymbolFamily[0] . '">' . $currentSymbolFamily[1] . '</option>';
    foreach ($symbolFamily as $symbol) {
        if ($symbol[0] != $currentSymbolFamily[0]) {
            echo '<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
        }
    }
    echo '</select></td>
    </tr>
    <tr>
        <td>Nazwa grupy: </td>
        <td><input type="text" id="name" name="name" value="' . $currentSymbolFamily[1] . '" ' . $dis . '></td>
    </tr>
    <tr>
        <td>Typ grupy:</td>
        <td><select id="is_visible" name="is_visible" ' . $dis . '>';
    switch ($currentSymbolFamily[2]) {
        case 0:
            echo '<option value=0>Grupa typu ON/OFF - output</option>
                  <option value=1>Grupa typu informacyjnego</option>                        
                  <option value=2>Grupa typu ON/OFF - input</option>';
            break;
        case 1:
            echo '<option value=1>Grupa typu informacyjnego</option>
                  <option value=0>Grupa typu ON/OFF - output</option>                                          
                  <option value=2>Grupa typu ON/OFF - input</option>';
            break;
        case 2:
            echo '<option value=2>Grupa typu ON/OFF - input</option>
                  <option value=1>Grupa typu informacyjnego</option>
                  <option value=0>Grupa typu ON/OFF - output</option>                                          
                  ';
            break;
    }

//    if ($currentSymbolFamily[2] == 0) {
//        echo '<option value=0>Grupa typu ON/OFF</option>
//                  <option value=1>Grupa typu informacyjnego</option>';
//    } else {
//        echo '<option value=1>Grupa typu informacyjnego</option>
//                  <option value=0>Grupa typu ON/OFF</option>';
//    }

    echo '</select></td>
    </tr>
    <tr>    
    <td colspan=2>';
    echo '<input type="hidden" id="id" name="id" value="' . $currentSymbolFamily[0] . ' "/>
        <button type="submit" id="send" name="send" value="' . $button_name . '">' . $button_name . '</button></td>
    </tr>
    </table>
    </form>';
}
?>