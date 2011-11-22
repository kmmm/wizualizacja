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
                return 'Dodano nowy symbol!';
            } else {
                return 'Nie udało dodać się nowego symbolu :(.';
            }
        } else {
            return 'W bazie istnieje już taki symbol!';
        }
    }

    function selectRecord($id_symbol_family, $value, $active) {
        $query = "SELECT * FROM symbol WHERE id_symbol_family='$id_symbol_family' and value='$value' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

}

?>
