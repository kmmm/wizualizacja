<?php
require_once 'userInterface.php';
require_once 'tables/tableUser.php';

$userInterface = new userInterface();

if($userInterface->login()){
$tableUser = new tableUser();

$title = "Panel administracyjny - zarządzanie użytkownikami";

$jquery = '<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
    $("#text3").delegate("#select_name", "change", function()
    {
        var id= $("#select_name").val();
	$("#text3").load("ajaxUser.php?id="+id);
    });
    
    $("#text3").delegate("#select_name_delete", "change", function()
    {
        var id= $("#select_name_delete").val();
	$("#text3").load("ajaxUser.php?id_delete="+id);
    });
});
</script>';

$headerTitle = "Panel administracyjny - zarządzanie użytkownikami";
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
                        <li><a href="user.php?action=add">Dodaj użytkownika</a></li>
                        <li><a href="user.php?action=edit">Edytuj użytkownika</a></li>
                        <li><a href="user.php?action=delete">Usuń użytkownika</a></li>
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
            if ($_POST['login'] != null && $_POST['password'] != null) {
                $alert = $tableUser->instert($_POST['login'], $_POST['password'], $_POST['user_type'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'edytuj':
            if ($_POST['login'] != null && $_POST['password'] != null && $_POST['id']!=null) {
                $alert = $tableUser->update($_POST['id'], $_POST['login'], $_POST['password'], $_POST['user_type'], 1);
            } else {
                $alert = 'Niepoprawnie wypełnione pola!';
            }
            break;
        case 'usuń':
            if ($_POST['id']!=null) {
                $alert = $tableUser->delete($_POST['id']);
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
        $form = '<form action="user.php?action=add" method="POST">
                    <table>
                    <tr>
                        <td>Nazwa użytkownika: </td>
                        <td><input type="text" id="login" name="login" /></td>
                    </tr>
                     <tr>
                        <td>Hasłó użytkownika: </td>
                        <td><input type="text" id="password" name="password" /></td>
                    </tr>
                    <tr>
                        <td>Typ grupy:</td>
                        <td><select id="user_type" name="user_type">
                        <option value=1>użytkownicy</option>
                        <option value=2>administratorzy</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
        $content = formFrame($form, 'Dodaj grupę symboli', $alert);
        break;
    case 'edit':
        $users = $tableUser->selectAllRecords();
        if (!empty($users)) {
            $form = '<form action="user.php?action=edit" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz użytkownika</td>
                        <td><select id="select_name" name="select_name">
                        <option>---</option>';
            foreach ($users as $user) {
                $form.='<option value ="' . $user[0] . '">' . $user[1] . '</option>';
            }
            $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Nazwa użytkownika: </td>
                        <td><input type="text" id="login" name="login" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td>Hasłó użytkownika: </td>
                        <td><input type="text" id="password" name="password" disabled=disabled/></td>
                    </tr>
                    <tr>
                        <td>Typ grupy:</td>
                        <td><select id="user_type" name="user_type" disabled="disabled">
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
        $users = $tableUser->selectAllRecords();
        if (!empty($users)) {
            $form = '<form action="user.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Wybierz użytkownika</td>
                        <td><select id="select_name_delete" name="select_name_delete">
                        <option>---</option>';
            foreach ($users as $user) {
                $form.='<option value ="' . $user[0] . '">' . $user[1] . '</option>';
            }
            $form.='</select></td>
                    </tr>
                    <tr>
                        <td>Login: </td>
                        <td><input type="text" id="login" name="login" disabled="disabled"/></td>
                    </tr>
                    <tr>
                        <td>Hasło: </td>
                        <td><input type="text" id="password" name="password" disabled="disabled"/></td>
                    </tr>
                    <tr>
                        <td>Typ grupy:</td>
                        <td><select id="user_type" name="user_type" disabled="disabled">
                        <option>---</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="usuń"/>usuń</button></td>
                    </tr>
                    </table>
                 </form>';
        } else {
            $form = '<h3>Baza danych nie zawiera żandych użytkowników.</h3>';
        }
        $content = formFrame($form, 'Usuń użytkownika', $alert);
        break;
    default:
        $minUserPrivleges = x;
        break;
}

$menu = $userInterface->leftMenuAdminPanel();

$userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}

?>