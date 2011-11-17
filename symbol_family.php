<?php

/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */

require_once 'userInterface.php';
require_once 'tableSymbolFamily.php';

$userInterface = new userInterface();
$tableSymbolFamily = new tableSymbolFamily();

$title = "Panel administracyjny - zarządzanie grupami symboli";
$jquery = null;
$headerTitle = "Panel administracyjny - zarządzanie grupami symboli";
$divBackground = null;
$alert = null;

/**
 * Żeby nie powtarzać tych samych divów (jakkolwiek skomplikowanie to wygląda ;/) metoda. 
 * @param type $form
 * @return string 
 */

function formFrame($form, $divTitle, $alert){
    $content='
            <div class="center">
                <div class="title2">'.$divTitle.'</div>
                <div class="text2">
                    <div class="center">
                        <div class="text3">
                        <li><a href="symbol_family.php">Dodaj grupę symboli</a></li>
                        <li><a href="symbol_family.php?action=edit">Edytuj grupę symboli</a></li>
                        <li><a href="symbol_family.php?action=delete">Usuń grupę symboli</a></li>
                        </div>
                        <div class="text3">
                        <alert>'.$alert.'</alert><br>'
                        .$form.
                        '</div>
                    </div>
                </div>
            </div>';
    return $content;
}

/*
 * Kod strony
 */
if(isset($_POST['send'])){
    switch ($_POST['send']){
        case 'add':
            $alert = $tableSymbolFamily->instert($_POST['name'], $_POST['is_visible'], 1);
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
        $content= formFrame($form, 'Dodaj grupę symboli', $alert) ;
        break;
    case 'edit':
        $form = 'formlarz edycji';
        $content= formFrame($form) ;
        break;
    case 'delete':
        $form = 'formularz usuwania';
        $content= formFrame($form) ;
        break;
    default:
        $content= 'def';
        break;
}

$menu = $userInterface->leftMenuAdminPanel();

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground);


?>
