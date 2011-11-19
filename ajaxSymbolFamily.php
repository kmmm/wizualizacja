<?php

require_once('tables/tableSymbolFamily.php');

if ($_GET['id'] != null) {
    $tableSymbolFamily = new tableSymbolFamily();
    $symbolFamily = $tableSymbolFamily->selectAllRecords();
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($_GET['id']);
    echo '<form action="symbol_family.php?action=edit" method="POST">
                <table>
                    <tr>
                        <td>Wybierz symbol</td>
                        <td><select id="select_name" name="select_name">
                                <option value="' . $currentSymbolFamily[0] . '">' . $currentSymbolFamily[1] . '</option>';
                                foreach ($symbolFamily as $symbol) {
                                if ($symbol[0] != $currentSymbolFamily[0]) {
                                        echo '<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
                                    }
                                }
    echo '</select></td>
    </tr>
    <tr>
        <td>Podaj nazwę grupy: </td>
        <td><input type="text" id="name" name="name" value="'.$currentSymbolFamily[1].'"/></td>
    </tr>
    <tr>
        <td>Podaj typ grupy:</td>
        <td><select id="is_visible" name="is_visible">';
    switch ($currentSymbolFamily[2]){
        case '0':
            echo '<option value=0>Grupa typu ON/OFF</option>
                  <option value=1>Grupa typu informacyjnego</option>';
            break;
        case '1':
            echo '<option value=1>Grupa typu informacyjnego</option>
                  <option value=0>Grupa typu ON/OFF</option>';
            break;
    }
        
    echo '</select></td>
    </tr>
    <tr>    
    <td colspan=2>
    <input type="hidden" id="id" name="id" value="'.$currentSymbolFamily[0].'"/>
        <button type="submit" id="send" name="send" value="edit">Edytuj</button></td>
    </tr>
    </table>
    </form>';
}
?>