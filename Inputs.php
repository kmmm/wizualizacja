<?php
session_start();
require_once 'userInterface.php';
require_once 'tables/tableInputs.php';
require_once 'tables/tableDevice.php';

$userInterface = new userInterface();

if($userInterface->login()){
$tableInputs = new tableInputs();
$tableDevice = new tableDevice();

$title = "Panel administracyjny - zarządzanie wejściami";

$jquery = '<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
    $("#text3").delegate("#select_name", "change", function()
    {
        var id= $("#select_name").val();
	$("#text3").load("ajaxInputs.php?id="+id);
    });
    
    $("#text3").delegate("#select_name_delete", "change", function()
    {
        var id= $("#select_name_delete").val();
	$("#text3").load("ajaxInputs.php?id_delete="+id);
    });
});
</script>';

$headerTitle = "Panel administracyjny - zarządzanie wejściami";
$divBackground = null;
$alert = null;
$minUserPrivleges = '2';

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
                        <li><a href="Inputs.php?action=add">Dodaj wejście</a></li>
                        <li><a href="Inputs.php?action=edit">Edytuj wejście</a></li>
                        <li><a href="Inputs.php?action=delete">Usuń wejście</a></li>
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
            if ($_POST['id_device'] != null) {
                $alert = $tableInputs->instert($_POST['name'],$_POST['id_device'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'edytuj':
            if ($_POST['name'] != null && $_POST['id']!=null) {
                $alert = $tableInputs->update($_POST['id'], $_POST['name'],$_POST['id_device'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'usuń':
            if ($_POST['id']!=null) {
                $alert = $tableInputs->delete($_POST['id']);
            } else {
                $alert = 'Niepoprawnie wypełnione pola! ';
            }
            break;
        default:
            break;
    }
}

switch ($_GET['action']) {    
    case 'add':
        $devices=$tableDevice->selectAllRecords();
        if(!empty($devces)){
        $form = '<form action="Inputs.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Nazwa wejścia: </td>
                        <td><input type="text" id="name" name="name" /></td>
                    </tr>

                    <tr>
                        <td>Port urządzenia:</td>
                        <td><select id="id_device" name="id_device">';
        
        foreach($devices as $device){
                     $form.='   <option value="'.$device[0].'">'.$device[1].'</option>';
        }        
        $form.='    </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
        }else{
            $form = '<h3>Baza danych nie zawiera żandych urządzeń.</h3>';
        }
        $content = formFrame($form, 'Dodaj grupę symboli', $alert);
        break;
    case 'edit':
        $Inputs = $tableInputs->selectAllRecords();
        if (!empty($Inputs)) {
            $form = '<form action="Inputs.php?action=edit" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz wejście</td>
                        <td><select id="select_name" name="select_name">
                        <option>---</option>';
            foreach ($Inputs as $input) {
                $form.='<option value ="' . $input['id'] . '">' . $input['name'] . '</option>';
            }
            $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Nazwa wejścia: </td>
                        <td><input type="text" id="name" name="name" disabled="disabled"></td>
                    </tr>

                    <tr>
                        <td>Port Urządzenia:</td>
                        <td><select id="id_device" name="id_device" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="edytuj"/>edytuj</button></td>
                    </tr>
                    </table>
                 </form>';
        } else {
            $form = '<h3>Baza danych nie zawiera żandych wejść.</h3>';
        }
        $content = formFrame($form, 'Edytuj wejścia', $alert);
        break;
    case 'delete':
        $Inputs = $tableInputs->selectAllRecords();
        if (!empty($Inputs)) {
            $form = '<form action="Inputs.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz wejście</td>
                        <td><select id="select_name_delete" name="select_name_delete">
                        <option>---</option>';
            foreach ($Inputs as $input) {
                $form.='<option value ="' . $input['id'] . '">' . $input['name'] . '</option>';
            }
            $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Nazwa wejścia: </td>
                        <td><input type="text" id="name" name="name" disabled="disabled"/></td>
                    </tr>

                    <tr>
                        <td>Port urządzenia:</td>
                        <td><select id="id_device" name="id_device" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="usuń"/>usuń</button></td>
                    </tr>
                    </table>
                 </form>';
        } else {
            $form = '<h3>Baza danych nie zawiera żandych wejść.</h3>';
        }
        $content = formFrame($form, 'Usuń wejście', $alert);
        break;
    default:
        $minUserPrivleges = x;
        break;
}

$menu = $userInterface->leftMenuAdminPanel();

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}

?>
