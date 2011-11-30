<?php
require_once('connectDb.php');
$connectDB = new connectDb();

/*
 * Klasa z funkcjami zapytań dla tabeli symbol family
 */

class tableVisualisation {

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
                return 'Nie udało dodać sie nowego użytkownika :(';
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
        $query = "SELECT * FROM device WHERE active=1";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $symbol[$licznik]['id'] = $row[0];    //id
            $symbol[$licznik]['port'] = $row[1];    //port
            $symbol[$licznik]['type'] = $row[2];    //type
            $symbol[$licznik]['value'] = $row[3];    //value
            $symbol[$licznik]['get_value'] = $row[4];    //get_value
            $symbol[$licznik]['set_value'] = $row[5];    //set_value            
            $licznik++;
        }
        return $symbol;
    }

    function update($id, $login, $user_type, $active) {
        $row = $this->selectRecord($name, $user_type, $active);
        if (empty($row) || $row[0] == $id) {
            $query = "UPDATE user SET login='$login', id_user_type='$user_type', active='$active' WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Wyedytowano użytkownika';
            } else {
                return 'Nie udało się wyedytować użytkownika.';
            }
        } else {
            return 'W bazie istnieje już taka grupa symboli!';
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

}

?>