<?php

require_once('connectDb.php');


$connectDB = new connectDb();

/*
 * Klasa z funkcjami zapytań dla tabeli symbol family
 */

class tableSymbolFamily {

    /**
     * funkcja dodająca symbol do bazy
     * @param type $name
     * @param type $is_visible
     * @param type $active
     * @return type 
     */
    function instert($name, $is_visible, $active) {
        $row = $this->selectRecord($name, $is_visible, $active);
        if (empty($row)) {
            $query = "INSERT INTO symbol_family values ('','$name', '$is_visible', '$active')";
            $result = mysql_query($query);
            if ($result) {
                return 'Dodano nową grupę symboli!';
            } else {
                return 'Nie udało dodać sie nowej grupy symboli :(';
            }
        } else {
            return 'W bazie istnieje już taka grupa symboli!';
        }
    }

    function selectRecord($name, $is_visible, $active) {
        $query = "SELECT * FROM symbol_family WHERE name='$name' and is_visible='$is_visible' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM symbol_family WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectAllRecords() {
        $query = "SELECT * FROM symbol_family WHERE active=1";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $symbol[$licznik][0] = $row[0];    //id
            $symbol[$licznik][1] = $row[1];    //name
            $symbol[$licznik][2] = $row[2];    //is_visible
            $symbol[$licznik][3] = $row[3];    //active
            $licznik++;
        }
        return $symbol;
    }

    function update($id, $name, $is_visible, $active) {
        $row = $this->selectRecord($name, $is_visible, $active);
        if (empty($row) || $row[0] == $id) {
            $query = "UPDATE symbol_family SET name='$name', is_visible='$is_visible', active='$active'
            WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Wyedytowano grupę symboli!';
            } else {
                return 'Nie udało się wyedytować grupy symboli.';
            }
        } else {
            return 'W bazie istnieje już taka grupa symboli!';
        }
    }

    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM symbol_family WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Usunięto grupę symboli!';
            } else {
                return 'Nie udało się usunąć grupy symboli.';
            }
        } else {
            return 'W bazie nie ma takiej grupy symboli!';
        }
    }

}

?>