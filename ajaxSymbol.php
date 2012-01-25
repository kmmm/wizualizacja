<?php

require_once 'tables/tableSymbolFamily.php';
require_once 'tables/tableSymbol.php';

$tableSymbolFamily = new tableSymbolFamily();
$tableSymbol = new tableSymbol();

$currentSymbol = null;

if (isset($_GET['id'])) {

    $symbolFamily = $tableSymbolFamily->selectAllRecords();
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($_GET['id']);
    echo '<h4></h4><br><form action="symbol.php?action=add" method="POST" enctype="multipart/form-data">
        <table>
        <tr>
            <td>Grupa: </td>
            <td><select id="select_symbolfamily" name="select_symbolfamily">
                <option value="' . $currentSymbolFamily[0] . '">' . $currentSymbolFamily[1] . '</option>';
    foreach ($symbolFamily as $symbol) {
        if ($symbol[0] != $currentSymbolFamily[0]) {
            echo '<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
        }
    }
    echo '</select></td>
                    </tr>';
    if (!empty($currentSymbolFamily) && ($currentSymbolFamily[2] == 0 || $currentSymbolFamily[2] == 2)) {
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
if (isset($_GET['id_delete']) || isset($_GET['id_symbol'])) {
    if (isset($_GET['id_symbol'])) {
        $symbolData = $tableSymbol->selectRecordById($_GET['id_symbol']);
        $id = $symbolData[1];
    } else {
        $id = $_GET['id_delete'];
    }
    $symbolFamily = $tableSymbolFamily->selectAllRecords();
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($id);
    echo '<h4></h4><br><form action="symbol.php?action=delete" method="POST" enctype="multipart/form-data">
        <table>
        <tr>
            <td>Grupa: </td>
            <td><select id="select_symbolfamily_delete" name="select_symbolfamily_delete">
                <option value="' . $currentSymbolFamily[0] . '">' . $currentSymbolFamily[1] . '</option>';
    foreach ($symbolFamily as $symbol) {
        if ($symbol[0] != $currentSymbolFamily[0]) {
            echo '<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
        }
    }
    echo '</select></td>
                    </tr>';
    $symbols = $tableSymbol->selectAllRecordsBySumbolFamilyId($currentSymbolFamily[0]);
    if (!empty($symbols)) {
        echo '<tr><td>Symbol:</td><td><select id="select_symbol" name="select_symbol">';
        if (isset($_GET['id_symbol'])) {
            switch ($symbolData[3]) {
                case -1:
                    echo'<option value="' . $symbolData[0] . '">domyślny</option>';
                    break;
                case 0:
                    echo'<option value="' . $symbolData[0] . '">OFF</option>';
                    break;
                case 1:
                    echo'<option value="' . $symbolData[0] . '">ON</option>';
                    break;
            }
            $currentSymbol = $symbolData[3];
        } else {
            echo '<option>---</option>';
        }
        foreach ($symbols as $sym) {
            if ($currentSymbol != $sym[3]) {
                switch ($sym[3]) {
                    case -1:
                        echo'<option value="' . $sym[0] . '">domyślny</option>';
                        break;
                    case 0:
                        echo'<option value="' . $sym[0] . '">OFF</option>';
                        break;
                    case 1:
                        echo'<option value="' . $sym[0] . '">ON</option>';
                        break;
                }
            }
        }
        echo '</td>
        </tr>';
        if (isset($_GET['id_symbol'])) {
            echo '<tr><td colspan=2><img src="' . $symbolData[2] . '">';
        }
        echo'<tr>
            <td colspan=2><button type="submit" id="send" name="send" value="usuń" onclick="this.value=add">usuń</button></td>
        </tr>';
    } else {
        echo '<tr><td colspan=2><h4>Brak symboli dla tej grupy.</h4></td></tr>';
    }
    echo '</table>
    </form>';
}
?>