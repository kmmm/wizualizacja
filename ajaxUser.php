<?php

require_once('tables/tableUser.php');

if (isset($_GET['id']) || isset($_GET['id_delete'])) {
    if (isset($_GET['id'])) {
        $select = "select_name";
        $id = $_GET['id'];
        $dis = null;
        $button_name = "edytuj";
        $value = "edit";
    } else {
        $select = "select_name_delete";
        $id = $_GET['id_delete'];
        $dis = "disabled=disabled";
        $button_name = "usuń";
        $value = "delete";
    }


    $tableUser = new tableUser();
    $users = $tableUser->selectAllRecords();
    $currentUser = $tableUser->selectRecordById($id);
    echo '<h4></h4><br><form action="user.php?action=' . $value . '" method="POST">
                <table>
                    <tr>
                        <td>Wybierz użytkownika</td>
                        <td><select id="' . $select . '" name="' . $select . '">
                                <option value="' . $currentUser[0] . '">' . $currentUser[1] . '</option>';
    foreach ($users as $user) {
        if ($user[0] != $currentUser[0]) {
            echo '<option value ="' . $user[0] . '">' . $user[1] . '</option>';
        }
    }
    echo '</select></td>
    </tr>
    <tr>
        <td>Login: </td>
        <td><input type="text" id="login" name="login" value="' . $currentUser[1] . '" ' . $dis . '></td>
    </tr>
    <tr>
        <td>Hasło: </td>
        <td><input type="hidden" id="password" name="password" value="' . $currentUser[2] . '" ' . $dis . '></td>
    </tr>    

    <tr>
        <td>Typ grupy:</td>
        <td><select id="user_type" name="user_type" ' . $dis . '>';
    if ($currentUser[3] == 1) {
        echo '<option value=1>użytkownik</option>
                  <option value=2>adinistrator</option>';
    } else {
        echo '<option value=2>administrator</option>
                  <option value=1>użytkownik</option>';
    }

    echo '</select></td>
    </tr>
    <tr>    
    <td colspan=2>';
    echo '<input type="hidden" id="id" name="id" value="' . $currentUser[0] . ' "/>
        <button type="submit" id="send" name="send" value="' . $button_name . '">' . $button_name . '</button></td>
    </tr>
    </table>
    </form>';
}
?>