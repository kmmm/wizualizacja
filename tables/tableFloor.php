<?php

require_once('connectDb.php');
$connectDB = new connectDb();

/*
 * Klasa z funkcjami zapytań dla tabeli floor
 */

class tableFloor {

    function instert($number, $link_photo, $active) {
        $row = $this->selectRecord($number, $active);
        if (empty($row)) {
            $query = "INSERT INTO floor values ('','$number', '$link_photo', '$active')";
            $result = mysql_query($query);
            if ($result) {
                $ret[0] = 1;
                $ret[1] = 'Dodano nowe piętro!';
                return $ret;
            } else {
                $ret[0] = 0;
                $ret[1] = 'Nie udało dodać się nowego piętra.';
                return $ret;
            }
        } else {
            $ret[0] = 0;
            $ret[1] = 'W bazie istnieje już takie piętro!';
            return $ret;
        }
    }

    function selectRecord($number, $active) {
        $query = "SELECT * FROM floor WHERE number='$number' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM floor WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectAllRecords() {
        $query = "SELECT * FROM floor ORDER BY number ASC";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $symbol[$licznik][0] = $row[0];    //id
            $symbol[$licznik][1] = $row[1];    //name
            $symbol[$licznik][2] = $row[2];    //id_device
            $symbol[$licznik][3] = $row[3];    //id_symbol_family
            $licznik++;
        }
        return $symbol;
    }
    
    function selectFloorImageByFloorNumber($number){
        $query = "SELECT * FROM floor WHERE number='$number'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM floor WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                $ret[0] = 1;
                $ret[1] = 'Usunięto kondygnacjęl!';
                return $ret;
            } else {
                $ret[0] = 1;
                $ret[1] = 'Nie udało się usunąć kondygnacji.';
                return $ret;
            }
        } else {
            $ret[0] = 1;
            $ret[1] = 'W bazie nie ma takiej kondygnacji!';
            return $ret;
        }
    }

}

?>
