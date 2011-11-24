<?php

/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */
require_once 'userInterface.php';
require_once 'tables/tableSymbolFamily.php';

$userInterface = new userInterface();

if($userInterface->login()){
$tableSymbolFamily = new tableSymbolFamily();

$title = "Panel administracyjny - zarządzanie grupami symboli";

$jquery = '<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
   
    $("#text3").delegate("#select_name", "change", function()
    {
        var id= $("#select_name").val();
	$("#text3").load("ajaxSymbolFamily.php?id="+id);
    });
    
    $("#text3").delegate("#select_name_delete", "change", function()
    {
        var id= $("#select_name_delete").val();
	$("#text3").load("ajaxSymbolFamily.php?id_delete="+id);
    });
});
</script>';

$headerTitle = "Panel administracyjny - zarządzanie grupami symboli";
$divBackground = null;
$alert = null;
$minUserPrivleges = '100';

$link ='<li><a href="symbol_family.php?action=add">Dodaj grupę symboli</a></li>
        <li><a href="symbol_family.php?action=edit">Edytuj grupę symboli</a></li>
        <li><a href="symbol_family.php?action=delete">Usuń grupę symboli</a></li>';

/*
 * Kod strony
 */
//$_POST['send'] ma polskie wartości tylko dlatego, że IE8 to najgłupsza przeglądarka pod słońcem i zastanawiam się
//jak to się dzieje, że ludzie jeszcze jej używają. 
if (isset($_POST['send'])) {
    switch ($_POST['send']) {
        case 'dodaj':
            if ($_POST['name'] != null && $_POST['is_visible'] != null) {
                $alert = $tableSymbolFamily->instert($_POST['name'], $_POST['is_visible'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'edytuj':
            if ($_POST['name'] != null && $_POST['is_visible'] != null && $_POST['id']!=null) {
                $alert = $tableSymbolFamily->update($_POST['id'], $_POST['name'], $_POST['is_visible'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'usuń':
            if ($_POST['id']!=null) {
                $alert = $tableSymbolFamily->delete($_POST['id']);
            } else {
                $alert = 'Niepoprawnie wypełnione pola! :(';
            }
            break;
        default:
            break;
    }
}

switch ($_GET['action']) {
    case 'add':
        $form = '<form action="symbol_family.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Nazwa grupy: </td>
                        <td><input type="text" id="name" name="name" /></td>
                    </tr>
                    <tr>
                        <td>Typ grupy:</td>
                        <td><select id="is_visible" name="is_visible">
                        <option value=0>Grupa typu ON/OFF</option>
                        <option value=1>Grupa typu informacyjnego</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
        $content = $userInterface->adminPanelFormFrame($link, $form, 'Dodaj grupę symboli', $alert);
        break;
    case 'edit':
        $symbolFamily = $tableSymbolFamily->selectAllRecords();
        if (!empty($symbolFamily)) {
            $form = '<form action="symbol_family.php?action=edit" method="POST">
                    <table>
                    <tr>
                        <td>Grupa symboli:</td>
                        <td><select id="select_name" name="select_name">
                        <option>---</option>';
            foreach ($symbolFamily as $symbol) {
                $form.='<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
            }
            $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Nazwa grupy: </td>
                        <td><input type="text" id="name" name="name" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td>Typ grupy:</td>
                        <td><select id="is_visible" name="is_visible" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="edytuj"/>edytuj</button></td>
                    </tr>
                    </table>
                 </form>';
        } else {
            $form = '<h3>Baza danych nie zawiera żandych grup symboli.</h3>';
        }
          $content = $userInterface->adminPanelFormFrame($link, $form, 'Edytuj grupę symboli', $alert);
        break;
    case 'delete':
        $symbolFamily = $tableSymbolFamily->selectAllRecords();
        if (!empty($symbolFamily)) {
            $form = '<form action="symbol_family.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Grupa symboli:</td>
                        <td><select id="select_name_delete" name="select_name_delete">
                        <option>---</option>';
            foreach ($symbolFamily as $symbol) {
                $form.='<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
            }
            $form.='</select></td>
                    </tr>
                                        <tr>
                        <td>Nazwa grupy: </td>
                        <td><input type="text" id="name" name="name" disabled="disabled"/></td>
                    </tr>
                    <tr>
                        <td>Typ grupy:</td>
                        <td><select id="is_visible" name="is_visible" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="usuń"/>usuń</button></td>
                    </tr>
                    </table>
                 </form>';
        } else {
            $form = '<h3>Baza danych nie zawiera żandych grup symboli.</h3>';
        }
          $content = $userInterface->adminPanelFormFrame($link, $form, 'Usuń grupę symboli', $alert);
        break;
    default:
        $minUserPrivleges = x;
        break;
}

$menu = $userInterface->leftMenuAdminPanel();

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
