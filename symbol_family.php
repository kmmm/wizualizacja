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
});
</script>';

$headerTitle = "Panel administracyjny - zarządzanie grupami symboli";
$divBackground = null;
$alert = null;
$minUserPrivleges = '100';

/**
 * Żeby nie powtarzać tych samych divów (jakkolwiek skomplikowanie to wygląda ;/) metoda. 
 * @param type $form
 * @return string 
 */
function formFrame($form, $divTitle, $alert) {
    $content = '
            <div class="center">
                <div class="title2">' . $divTitle . '</div>
                <div class="text2">
                    <div class="center">
                        <div class="text3">
                        <li><a href="symbol_family.php?action=add">Dodaj grupę symboli</a></li>
                        <li><a href="symbol_family.php?action=edit">Edytuj grupę symboli</a></li>
                        <li><a href="symbol_family.php?action=delete">Usuń grupę symboli</a></li>
                        </div>
                        <div class="text3" id="text3">
                        <alert>' . $alert . '</alert><br>'
            . $form .
            '</div>
                    </div>
                </div>
            </div>';
    return $content;
}

/*
 * Kod strony
 */
if (isset($_POST['send'])) {
    switch ($_POST['send']) {
        case 'add':
            if ($_POST['name'] != null && $_POST['is_visible'] != null) {
                $alert = $tableSymbolFamily->instert($_POST['name'], $_POST['is_visible'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'edit':
            if ($_POST['name'] != null && $_POST['is_visible'] != null) {
                $alert = $tableSymbolFamily->update($_POST['id'], $_POST['name'], $_POST['is_visible'], 1);
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
        $form = '<form action="symbol_family.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Podaj nazwę grupy: </td>
                        <td><input type="text" id="name" name="name" /></td>
                    </tr>
                    <tr>
                        <td>Podaj typ grupy:</td>
                        <td><select id="is_visible" name="is_visible">
                        <option value=0>Grupa typu ON/OFF</option>
                        <option value=1>Grupa typu informacyjnego</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="add"/>Dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
        $content = formFrame($form, 'Dodaj grupę symboli', $alert);
        break;
    case 'edit':
        $symbolFamily = $tableSymbolFamily->selectAllRecords();
        if (!empty($symbolFamily)) {
            $form = '<form action="symbol_family.php?action=edit" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz symbol</td>
                        <td><select id="select_name" name="select_name">
                        <option>---</option>';
            foreach ($symbolFamily as $symbol) {
                $form.='<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
            }
            $form.='</select></td>
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
        } else {
            $form = '<e1>Baza danych nie zawiera żandych grup symboli.</e1>';
        }
        $content = formFrame($form, 'Edytuj grupę symboli', $alert);
        break;
    case 'delete':
        $form = 'formularz usuwania';
        $content = formFrame($form);
        break;
    default:
        $minUserPrivleges = x;
        break;
}

$menu = $userInterface->leftMenuAdminPanel();

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
