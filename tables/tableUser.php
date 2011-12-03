<?php

require_once('connectDb.php');
$connectDB = new connectDb();

/*
 * Klasa z funkcjami zapytań dla tabeli symbol family
 */

class tableUser {

    /**
     * funkcja dodająca symbol do bazy
     * @param type $name
     * @param type $is_visible
     * @param type $active
     * @return type 
     */
    function instert($login, $password, $user_type, $active) {
        $row = $this->selectRecord($login, $user_type, $active);
        if (empty($row)) {
            //    $password = crypt($password,  CRYPT_MD5);
            $query = "INSERT INTO user values ('','$login', '$password', '$user_type', '$active')";
            $result = mysql_query($query);
            if ($result) {
                return 'Dodano nowego użytkownika!';
            } else {
                return 'Nie udało dodać sie nowego użytkownika';
            }
        } else {
            return 'W bazie istnieje już taki użytkownik';
        }
    }

    function selectRecord($login, $active) {
        $query = "SELECT * FROM symbol_family WHERE login='$login' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM user WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectAllRecords() {
        $query = "SELECT * FROM user WHERE active=1";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        $user = "";
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $user[$licznik][0] = $row[0];    //id
            $user[$licznik][1] = $row[1];    //login
            $user[$licznik][2] = $row[2];    //password
            $user[$licznik][3] = $row[3];    //user_type
            $user[$licznik][4] = $row[4];    //active
            $licznik++;
        }
        return $user;
    }

    function update($id, $login, $user_type, $active) {
        $row = $this->selectRecord($name, $user_type, $active);
        if (empty($row) || $row[0] == $id) {
            $query = "UPDATE user SET login='$login', user_type='$user_type', active='$active' WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Wyedytowano użytkownika';
            } else {
                return 'Nie udało się wyedytować użytkownika.';
            }
        } else {
            return 'W bazie istnieje już taki użytkownik!';
        }
    }

    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM user WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Usunięto użytkownika!';
            } else {
                return 'Nie udało się usunąć użytkownika.';
            }
        } else {
            return 'W bazie nie ma takiego użytkownika!';
        }
    }

    function updatePass($login, $old_pass, $pass) {
        $query = "SELECT * FROM user WHERE login = '$login' AND password ='$old_pass'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        if (empty($row)) {
            return '<h43>Podano niepoprawne stare hasło.</h4>';
        } else {
            $query = "UPDATE user SET password='$pass' WHERE login='$login'";
            $result = mysql_query($query);
            if ($result) {
                return '<h4>Hasło zaktualizowano poprawnie!</h4>';
            } else {
                return '<h4>Niestety, nie udało się zaktualizować hasła.</h4>';
            }
        }
    }

}

?>