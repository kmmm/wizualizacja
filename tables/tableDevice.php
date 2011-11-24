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
}

?>
