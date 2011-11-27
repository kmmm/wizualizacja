<?php

require_once('connectDb.php');
$connectDB = new connectDb();

/*
 * Klasa z funkcjami zapytań dla tabeli symbol
 */

class tableSymbol {

    function instert($id_symbol_family, $link_photo, $value, $active) {
        $row = $this->selectRecord($id_symbol_family, $value, $active);
        if (empty($row)) {
            $query = "INSERT INTO symbol values ('','$id_symbol_family', '$link_photo', '$value', '$active')";
            $result = mysql_query($query);
            if ($result) {
                $ret[0] = 1;
                $ret[1] = 'Dodano nowy symbol!';
                return $ret;
            } else {
                $ret[0] = 0;
                $ret[1] = 'Nie udało dodać się nowego symbolu :(.';
                return $ret;
            }
        } else {
            $ret[0] = 0;
            $ret[1] = 'W bazie istnieje już taki symbol!';
            return $ret;
        }
    }

    function selectRecord($id_symbol_family, $value, $active) {
        $query = "SELECT * FROM symbol WHERE id_symbol_family='$id_symbol_family' and value='$value' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM symbol WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectAllRecords() {
        $query = "SELECT * FROM symbol";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $symbol[$licznik][0] = $row[0];    //id
            $symbol[$licznik][1] = $row[1];    //id_symbol_family
            $symbol[$licznik][2] = $row[2];    //link_photo
            $symbol[$licznik][3] = $row[3];    //value
            $symbol[$licznik][4] = $row[4];    //active
            $licznik++;
        }
        return $symbol;
    }

    function selectAllRecordsBySumbolFamilyId($id_symbol_family) {
        $query = "SELECT * FROM symbol WHERE id_symbol_family='$id_symbol_family'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $symbol[$licznik][0] = $row[0];    //id
            $symbol[$licznik][1] = $row[1];    //id_symbol_family
            $symbol[$licznik][2] = $row[2];    //link_photo
            $symbol[$licznik][3] = $row[3];    //value
            $symbol[$licznik][4] = $row[4];    //active
            $licznik++;
        }
        return $symbol;
    }

    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM symbol WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                $ret[0] = 1;
                $ret[1] = 'Usunięto symbol!';
                return $ret;
            } else {
                $ret[0] = 1;
                $ret[1] = 'Nie udało się usunąć symbolu.';
                return $ret;
            }
        } else {
            $ret[0] = 1;
            $ret[1] = 'W bazie nie ma takiego symbolu!';
            return $ret;
        }
    }

}

?>
