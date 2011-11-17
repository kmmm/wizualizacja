<?php
require_once 'connectDb.php';
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
        $query = "SELECT * FROM symbol_family WHERE name='$name' and is_visible='$is_visible' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
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

}

?>
