<?php

/**
 * device - formularze zarządzania urządzeniami
 */
require_once 'userInterface.php';
require_once 'tables/tableDevice.php';

$userInterface = new userInterface();
$tableDevie = new tableDevice();


if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie urządzeniami";

    $jquery = '<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
    $("#text3").delegate("#select_symbolfamily", "change", function()
    {
        var id= $("#select_symbolfamily").val();
	$("#text3").load("ajaxSymbol.php?id="+id);
    });
    
    $("#text3").delegate("#select_symbolfamily_delete", "change", function()
    {
        var id= $("#select_symbolfamily_delete").val();
	$("#text3").load("ajaxSymbol.php?id_delete="+id);
    });
    
    $("#text3").delegate("#select_symbol", "change", function()
    {
        var id= $("#select_symbol").val();
	$("#text3").load("ajaxSymbol.php?id_symbol="+id);
    });
});
</script>';

    $headerTitle = "Panel administracyjny - zarządzanie urządzeniami";
    $divBackground = null;
    $alert = null;
    $minUserPrivleges = '100';
    $link = '<li><a href="device.php?action=add">Dodaj urządzenie</a></li>
             <li><a href="device.php?action=edit">Edytuj urządzenie</a></li>
             <li><a href="device.php?action=delete">Usuń urządzenie</a></li>';

    /*
     * Kod strony
     */
//$_POST['send'] ma polskie wartości tylko dlatego, że IE8 to najgłupsza przeglądarka pod słońcem i zastanawiam się
//jak to się dzieje, że ludzie jeszcze jej używają. 
    if (isset($_POST['send'])) {
        switch ($_POST['send']) {
            case 'dodaj':
                if ($_POST['port']!="" && $_POST['type']!="") {
                    $alert = $tableDevie->instert($_POST['port'], $_POST['type'], -1, 0, 0);
                }else{
                    $alert = 'Niepoprawnie wypełnione pola!';
                }
                break;
            case 'usuń':
                if ($_POST['select_symbol'] != "") {
                    $symbol = $tableSymbol->selectRecordById($_POST['select_symbol']);
                    $ret = $tableSymbol->delete($_POST['select_symbol']);
                    $alert = $ret[1];
                    if ($ret[0] == 1) {
                        unlink('./' . $symbol[2]);
                    }
                } else {
                    $alert = 'Niepoprawnie wypełnione pola!';
                }
                break;
            default:
                break;
        }
    }

    switch ($_GET['action']) {
        case 'add':
            $form = '<form action="device.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Port:</td>
                        <td><input type="text" id="port" name="port" /></td>
                    </tr>
                    <tr>
                        <td>Typ:</td>
                        <td><select id="type" name="type">
                            <option value="I">Input</option>
                            <option value="O">Output</option>
                            <option value="R">Register</option>
                            <option value="F">Flag</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Dodaj urządzenie', $alert);
            break;
        case 'delete':
            $symbolFamily = $tableSymbolFamily->selectAllRecords();
            if (!empty($symbolFamily)) {
                $form = '<form action="symbol.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Grupa: </td>
                        <td><select id="select_symbolfamily_delete" name="select_symbolfamily_delete">
                        <option>---</option>';
                foreach ($symbolFamily as $symbol) {
                    $form.='<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
                }
                $form.='</select></td>
                    </tr>
                    </table>
                 </form>';
            } else {
                $form = '<h3>Baza danych nie zawiera żandych grup symboli.</h3>';
            } $content = $userInterface->adminPanelFormFrame($link, $form, 'Usuń symbol', $alert);
            break;
        default:
            $minUserPrivleges = x;
            break;
    }

    $menu = $userInterface->leftMenuAdminPanel();

    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
