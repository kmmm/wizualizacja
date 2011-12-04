<?php
session_start();
/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */
require_once 'userInterface.php';
require_once 'tables/tableFloor.php';

$userInterface = new userInterface();
$tableFloor = new tableFloor();


if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie kondygnacjami";

    $jquery = "";
    $headerTitle = "Panel administracyjny - zarządzanie kondygnacjami";
    $divBackground = null;
    $alert = null;

    $minUserPrivleges = '2';

    $link = '<li><a href="floor.php?action=add">Dodaj kondygnację</a></li>
             <li><a href="floor.php?action=delete">Usuń kondygnację</a></li>';


    /*
     * Kod strony
     */
//$_POST['send'] ma polskie wartości tylko dlatego, że IE8 to najgłupsza przeglądarka pod słońcem i zastanawiam się
//jak to się dzieje, że ludzie jeszcze jej używają. 
    if (isset($_POST['send'])) {
        switch ($_POST['send']) {
            case 'dodaj':
                if (isset($_FILES['img']['tmp_name']) && $_POST['number'] != "") {
                    if (($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/png")) {
                        $number = rand(1, 10000);
                        $plik_ext = explode('.', $_FILES['img']['name']);
                        do {
                            $nazwa = "floor" . sha1(date("d.m.Y.H.i.s") . $plik_ext[0] . $number) . '.' . $plik_ext[1];
                        } while (file_exists($nazwa));
                        $ret = $tableFloor->instert($_POST['number'], "photo/" . $nazwa, 1);
                        $alert = $ret[1];
                        if ($ret[0] == 1) {
                            if (is_uploaded_file($_FILES['img']['tmp_name'])) {
                                move_uploaded_file($_FILES['img']['tmp_name'], "photo/$nazwa");
                                if ($_FILES['img']['size'][0] > 1067 || $_FILES['img']['size'][1] > 530) {
                                    include('SimpleImage.php');
                                    $image = new SimpleImage();
                                    $image->load('photo/' . $nazwa);
                                    $image->resize(1067, 530);
                                    $image->save('photo/' . $nazwa);
                                }
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
                if ($_POST['number'] != "") {
                    $floor = $tableFloor->selectRecordById($_POST['number']);
                    $ret = $tableFloor->delete($_POST['number']);
                    $alert = $ret[1];
                    if ($ret[0] == 1) {
                        unlink('./' . $floor[2]);
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
            $form = '<form action="floor.php?action=add" method="POST" enctype="multipart/form-data">
                    <table>
                    <tr>
                        <td>Numer piętra:</td>
                        <td><input type="text" name="number" id="number" size=25></td>
                    </tr>
                    <tr>
                        <td>Obrazek:</td>
                        <td><input type="file" name="img" id="img" size=25></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="dodaj">dodaj</button></td>
                    </tr>
                    </table>
                 </form>';
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Dodaj kondygnację', $alert);
            break;
        case 'delete':
            $floors = $tableFloor->selectAllRecords();
            if (!empty($floors)) {
                $form = '<form action="floor.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Piętro: </td>
                        <td><select id="number" name="number">';
                foreach ($floors as $floor) {
                    $form.='<option value ="' . $floor[0] . '">' . $floor[1] . '</option>';
                }
                $form.='</select></td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" id="send" name="send" value="usuń" onclick="this.value=add">usuń</button></td>
                    </tr>
                    </table>
                 </form>';
            } else {
                $form = '<h3>Baza danych nie zawiera żandych kondygnacji.</h3>';
            } $content = $userInterface->adminPanelFormFrame($link, $form, 'Usuń kondygnację', $alert);
            break;
        default:
            $minUserPrivleges = x;
            break;
    }

    $menu = $userInterface->leftMenuAdminPanel();

    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
