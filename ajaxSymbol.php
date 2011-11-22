<?php

require_once 'tables/tableSymbolFamily.php';
$tableSymbolFamily = new tableSymbolFamily();

if (isset($_GET['id'])) {

    $symbolFamily = $tableSymbolFamily->selectAllRecords();
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($_GET['id']);
    echo '<h4></h4><br><form action="symbol.php?action=add" method="POST" enctype="multipart/form-data">
        <table>
        <tr>
            <td>Grupa: </td>
            <td><select id="select_symbolfamily" name="select_symbolfamily">
                <option value="'.$currentSymbolFamily[0].'">' . $currentSymbolFamily[1] . '</option>';
    foreach ($symbolFamily as $symbol) {
        if ($symbol[0] != $currentSymbolFamily[0]) {
            echo '<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
        }
    }
    echo '</select></td>
                    </tr>';
    if (!empty($currentSymbolFamily) && $currentSymbolFamily[2] == 0) {
        echo'<tr>
                <td>Obrazek:</td>
                <td><input type="file" name="img" id="img" size=25></td>
            </tr>
            <tr>
                <td>Typ:</td>
                <td><select id="value" name="value">
                    <option value="-1">domyślny</option>
                    <option value="1">ON</option>
                    <option value="0">OFF</option></td>
            </tr>';
    } else {
        echo '<tr>
                <td>Obrazek:</td>
                <td><input type="file" name="img" id="img" size=25></td>
            </tr>
            <tr>
                <td>Typ:</td>
                <td><select id="value" name="value">
                    <option value="-1">domyślny</option>
            </tr>';
    }
    echo '<tr>
            <td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td>
          </tr>
        </table>
    </form>';
}
?>