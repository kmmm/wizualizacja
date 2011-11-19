<?php

require_once 'tables/tableSymbolFamily.php';
$tableSymbolFamily = new tableSymbolFamily();

if (isset($_GET['id'])) {
    echo 'ddd';
    $symbolFamily = $tableSymbolFamily->selectAllRecords();
    $currentSymbolFamily = $tableSymbolFamily->selectRecordById($_GET['id']);
    echo '<form action="symbol_family.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz symbol</td>
                        <td><select id="select_name" name="select_name">
                                <option value="' . $currentSymbolFamily[0] . '">' . $currentSymbolFamily[1] . '</option>';
    foreach ($symbolFamily as $symbol) {
        if ($symbol[0] != $currentSymbolFamily[0]) {
            $form.='<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
        }
    }
    echo '</select></td>
    </tr>
    <tr>
    <td>Podaj nazwę grupy: </td>
    <td><input type="text" id="name" name="name" /></td>
    </tr>
    <tr>
    <td>Podaj typ grupy:</td>
    <td><select id="is_visible" name="is_visible">
    <option>---</option>
    </select></td>
    </tr>
    <tr>
    <td colspan=2><button type="submit" id="send" name="send" value="edit"/>Edytuj</button></td>
    </tr>
    </table>
    </form>';
}
?>