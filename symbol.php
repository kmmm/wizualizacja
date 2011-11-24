<?php

/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */
require_once 'userInterface.php';
require_once 'tables/tableSymbolFamily.php';
require_once 'tables/tableSymbol.php';

$userInterface = new userInterface();
$tableSymbolFamily = new tableSymbolFamily();
$tableSymbol = new tableSymbol();


if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie symbolami";

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

    $headerTitle = "Panel administracyjny - zarządzanie symbolami";
    $divBackground = null;
    $alert = null;
    $minUserPrivleges = '100';
    $link = '<li><a href="symbol.php?action=add">Dodaj symbol</a></li>
             <li><a href="symbol.php?action=delete">Usuń symbol</a></li>';

    /*
     * Kod strony
     */
//$_POST['send'] ma polskie wartości tylko dlatego, że IE8 to najgłupsza przeglądarka pod słońcem i zastanawiam się
//jak to się dzieje, że ludzie jeszcze jej używają. 
    if (isset($_POST['send'])) {
        switch ($_POST['send']) {
            case 'dodaj':
                if (isset($_FILES['img']['tmp_name']) && isset($_POST['value']) && isset($_POST['select_symbolfamily'])) {
                    if (($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg")) {
                        $number = rand(1, 10000);
                        $plik_ext = explode('.', $_FILES['img']['name']);
                        do {
                            $nazwa = sha1(date("d.m.Y.H.i.s") . $plik_ext[0] . $number) . '.' . $plik_ext[1];
                        } while (file_exists($nazwa));
                        $ret = $tableSymbol->instert($_POST['select_symbolfamily'], "photo/" . $nazwa, $_POST['value'], 1);
                        $alert = $ret[1];
                        if ($ret[0] == 1) {
                            if (is_uploaded_file($_FILES['img']['tmp_name'])) {
                                move_uploaded_file($_FILES['img']['tmp_name'], "photo/$nazwa");
                            } else {
                                $alert = 'Nie udało się wgrać pliku na serwer';
                            }
                        }
                    } else {
                        $alert = 'Niepoprawnie format obrazków.';
                    }
                } else {
                    $alert = 'Niepoprawnie wybrane obrazki.';
                }
                break;
            case 'usuń':
                if ($_POST['select_symbol'] != null) {
                    $symbol = $tableSymbol->selectRecordById($_POST['select_symbol']);
                    $ret = $tableSymbol->delete($_POST['select_symbol']);
                    $alert = $ret[1];
                    if ($ret[0] == 1) {
                        unlink('./' . $symbol[2]);
                    }
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
            $symbolFamily = $tableSymbolFamily->selectAllRecords();
            if (!empty($symbolFamily)) {
                $form = '<form action="symbol.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Grupa: </td>
                        <td><select id="select_symbolfamily" name="select_symbolfamily">
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
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Dodaj symbol', $alert);
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
