<?php
session_start();
/**
 * device - formularze zarządzania urządzeniami
 */
require_once 'userInterface.php';
require_once 'tables/tableDevice.php';

$userInterface = new userInterface();
$tableDevice = new tableDevice();


if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie urządzeniami";

    $jquery = '$(document).ready(function(){    
   
    $("#text3").delegate("#select_port", "change", function()
    {
        var id= $("#select_port").val();
	$("#text3").load("ajaxDevice.php?id="+id);
    });
    
    $("#text3").delegate("#select_port_delete", "change", function()
    {
        var id= $("#select_port_delete").val();
	$("#text3").load("ajaxDevice.php?id_delete="+id);
    });
});';

    $headerTitle = "Panel administracyjny - zarządzanie urządzeniami";
    $divBackground = null;
    $alert = null;
    $minUserPrivleges = '2';
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
                if ($_POST['port'] != "" && $_POST['type'] != "") {
                    $alert = $tableDevice->instert($_POST['port'], $_POST['type'], -1, 0, 0);
                } else {
                    $alert = 'Niepoprawnie wypełnione pola!';
                }
                break;
            case 'edytuj':
                if ($_POST['port'] != "" && $_POST['type'] != "" && $_POST['id'] != "") {
                    $alert = $tableDevice->update($_POST['id'], $_POST['port'], $_POST['type']);
                } else {
                    $alert = 'Niepoprawnie wypełnione pola!';
                }
                break;
            case 'usuń':
                if ($_POST['id'] != "") {
                    $alert = $tableDevice->delete($_POST['id']);
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
                        <td>Numer portu:</td>
                        <td><input type="text" id="port" name="port" /></td>
                    </tr>
                    <tr>
                        <td>Typ:</td>
                        <td><select id="type" name="type">
                            <option value="I">Input</option>
                            <option value="O">Output</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
//                                        <option value="R">Register</option>
//                            <option value="F">Flag</option>

            $content = $userInterface->adminPanelFormFrame($link, $form, 'Dodaj urządzenie', $alert);
            break;
        case 'edit':
            $devices = $tableDevice->selectAllRecords();
            if (!empty($devices)) {
                $form = '<form action="device.php?action=edit" method="POST">
                    <table>
                    <tr>
                        <td>Port:</td>
                        <td><select id="select_port" name="select_port">
                        <option>---</option>';
                foreach ($devices as $device) {
                    $form.='<option value ="' . $device[0] . '">' . $device[1] . ' '.$device[2].'</option>';
                }
                $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Numer portu: </td>
                        <td><input type="text" id="port" name="port" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td>Typ:</td>
                        <td><select id="type" name="type" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="edytuj"/>edytuj</button></td>
                    </tr>
                    </table>
                 </form>';
            } else {
                $form = '<h3>Baza danych nie zawiera żandych urządzeń.</h3>';
            }
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Edytuj urządzenie', $alert);
            break;
        case 'delete':
            $devices = $tableDevice->selectAllRecords();
            if (!empty($devices)) {
                $form = '<form action="device.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Port:</td>
                        <td><select id="select_port_delete" name="select_port_delete">
                        <option>---</option>';
                foreach ($devices as $device) {
                    $form.='<option value ="' . $device[0] . '">' . $device[1] . ' '.$device[2].'</option>';
                }
                $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Numer portu: </td>
                        <td><input type="text" id="port" name="port" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td>Typ:</td>
                        <td><select id="type" name="type" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="usuń"/>usuń</button></td>
                    </tr>
                    </table>
                 </form>';
            } else {
                $form = '<h3>Baza danych nie zawiera żandych urządzeń.</h3>';
            }
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Usuń urządzenie', $alert);
            break;
        default:
            $minUserPrivleges = x;
            break;
    }

    $menu = $userInterface->leftMenuAdminPanel();

    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
