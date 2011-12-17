<?php

require_once('connectDb.php');
$connectDB = new connectDb();

class tableDevice {

    function instert($port, $type, $value, $get_value, $set_value) {
        $row = $this->selectRecord($port, $type);
        if (empty($row)) {
            $query = "INSERT INTO device values ('','$port', '$type', '$value', '$get_value', '$set_value')";
            $result = mysql_query($query);
            if ($result) {
                return 'Dodano nowe urządzenie!';
            } else {
                return 'Nie udało dodać sie nowego urządzenia :(';
            }
        } else {
            return 'W bazie istnieje już takie urządzenie!';
        }
    }

    function selectRecord($port, $type) {
        $query = "SELECT * FROM device WHERE port='$port' and type='$type'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM device WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectAllRecords() {
        $query = "SELECT * FROM device";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        $device=null;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $device[$licznik][0] = $row[0];    //id
            $device[$licznik][1] = $row[1];    //name
            $device[$licznik][2] = $row[2];    //is_visible
            $device[$licznik][3] = $row[3];    //active
            $licznik++;
        }
        return $device;
    }
    
    function selectRecordsByType($type) {
        $query = "SELECT * FROM device WHERE type='$type' ORDER BY port ASC ";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $device="";
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $device[$licznik][0] = $row[0];    //id
            $device[$licznik][1] = $row[1];    //name
            $device[$licznik][2] = $row[2];    //is_visible
            $device[$licznik][3] = $row[3];    //active
            $licznik++;
        }
        return $device;
    }

  //  function update($id, $port, $type, $value, $get_value, $set_value) {
    function update($id, $port, $type) {
        $row = $this->selectRecord($port, $type);
        if (empty($row) || $row[0] == $id) {
            $query = "UPDATE device SET port='$port', type='$type'
            WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Wyedytowano urządzenie!';
            } else {
                return 'Nie udało się wyedytować urządzenia.';
            }
        } else {
            return 'W bazie istnieje już takie urządzenie!';
        }
    }

    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM device WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Usunięto urządzenie!';
            } else {
                return 'Nie udało się usunąć urządzenia.';
            }
        } else {
            return 'W bazie nie ma takiego urządzenia!';
        }
    }

}

?>
