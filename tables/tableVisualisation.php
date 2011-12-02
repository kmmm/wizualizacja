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

    function selectAllRecordsByIdFloor($floor) {         
        
        $query = "SELECT * FROM element WHERE id_floor='$floor'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while($row = mysql_fetch_array($result, MYSQL_NUM)){
            $symbol[$licznik]['id'] = $row[0];    
            $symbol[$licznik]['name'] = $row[1];    
            $symbol[$licznik]['id_device'] = $row[2];    
            $symbol[$licznik]['id_symbol_family'] = $row[3];    
            $symbol[$licznik]['id_floor'] = $row[4];              
            $symbol[$licznik]['x'] = $row[5];    
            $symbol[$licznik]['y'] = $row[6];              
            $licznik++;
        }
        return $symbol;
    }    
    
//    function selectPositionElementById($id){
//        $query = "SELECT value FROM device WHERE id =(SELECT id_device FROM element WHERE id='$id')";
//        $result = mysql_query($query);
//        $ret_res = mysql_num_rows($result);
//        $row = mysql_fetch_array($result, MYSQL_NUM);
//        $value = $row[0];    //photo
//        return $value;        
//    }
    
    function selectValueElementById($id){
        $query = "SELECT value FROM device WHERE id =(SELECT id_device FROM element WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        $value = $row[0];    //photo
        return $value;        
    }
    
    function selectPhotoByElementByIdAndValue($id, $value){
        $query = "SELECT link_photo FROM symbol WHERE value='$value' and id_symbol_family = (SELECT id_symbol_family FROM element WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        $photo = $row[0];    //photo
        return $photo;        
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


}

?>