<?php

/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */
require_once 'userInterface.php';
require_once 'tables/tableSymbolFamily.php';

$userInterface = new userInterface();
$tableSymbolFamily = new tableSymbolFamily();


if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie symbolami";

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

    $headerTitle = "Panel administracyjny - zarządzanie symbolami";
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
                        <li><a href="symbol.php?action=add">Dodaj symbol</a></li>
                        <li><a href="symbol.php?action=edit">Edytuj symbol</a></li>
                        <li><a href="symbol.php?action=delete">Usuń symbol</a></li>
                        </div>
                        <div class="text3" id="text3">
                        <h4>' . $alert . '</h4><br>'
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
                if ($_POST['name'] != null && $_POST['is_visible'] != null && $_POST['id'] != null) {
                    $alert = $tableSymbolFamily->update($_POST['id'], $_POST['name'], $_POST['is_visible'], 1);
                } else {
                    $alert = 'Niepoprawnie wypełnione pola!';
                }
                break;
            case 'usuń':
                if ($_POST['name'] != null && $_POST['is_visible'] != null && $_POST['id'] != null) {
                    $alert = $tableSymbolFamily->update($_POST['id'], $_POST['name'], $_POST['is_visible'], 0);
                } else {
                    $alert = 'Niepoprawnie wypełnione pola! :(' . $_POST['id'] . $_POST['name'] . $_POST['is_visible'];
                }
                break;
            default:
                break;
        }
    }

    switch ($_GET['action']) {
        case 'add':
            $symbolFamily = $tableSymbolFamily->selectAllRecords();
            if (!empty($symbolFamily)) {
                $form = '<form action="symbol.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Grupa: </td>
                        <td><select id="select_family" name="select_name">
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
            }
            $content = formFrame($form, 'Dodaj symbol', $alert);
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
            $content = formFrame($form, 'Edytuj grupę symboli', $alert);
            break;
        case 'delete':
            $symbolFamily = $tableSymbolFamily->selectAllRecords();
            if (!empty($symbolFamily)) {
                $form = '<form action="symbol_family.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz symbol</td>
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
            $content = formFrame($form, 'Usuń grupę symboli', $alert);
            break;
        default:
            $minUserPrivleges = x;
            break;
    }

    $menu = $userInterface->leftMenuAdminPanel();

    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
