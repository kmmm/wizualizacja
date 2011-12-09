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

    function selectElementById($id){
        
        $query ="
SELECT  element.id, symbol.link_photo, device.value, symbol_family.is_visible, symbol.value
FROM symbol
JOIN symbol_family ON symbol.id_symbol_family = symbol_family.id
JOIN element ON element.id_symbol_family = symbol.id_symbol_family
JOIN device ON device.id = element.id_device WHERE symbol.value = device.value AND element.id = '$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
            $symbol['id'] = $row[0];    
            $symbol['link_photo'] = $row[1];    
            $symbol['value'] = $row[2];    
            $symbol['is_visible'] = $row[3];    
            $symbol['value'] = $row[4];                          
        
        return $symbol;        
    }
    function  selectDefaultRecordsByIdFloor($floor) { 
         $symbol=null;
//        $query = "SELECT * FROM element WHERE id_floor='$floor'";
         $query = "SELECT  element.id, symbol.link_photo, symbol_family.is_visible, element.position_x, element.position_y, device.value
FROM symbol
JOIN symbol_family ON symbol.id_symbol_family = symbol_family.id
JOIN element ON element.id_symbol_family = symbol.id_symbol_family
JOIN floor on element.id_floor = floor.id
JOIN device ON element.id_device = device.id         
WHERE symbol.value = -1 and floor.id = '$floor'";
          
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while($row = mysql_fetch_array($result, MYSQL_NUM)){
            $symbol[$licznik]['id'] = $row[0];    
            $symbol[$licznik]['link_photo'] = $row[1];    
            $symbol[$licznik]['is_visible'] = $row[2];                
            $symbol[$licznik]['x'] = $row[3];    
            $symbol[$licznik]['y'] = $row[4];       
            $symbol[$licznik]['value'] = $row[5];             
            $licznik++;
        }
        return $symbol;        
    }  








    function selectAllRecordsByIdFloor($floor) {         
        $symbol=null;
        $query = "SELECT * FROM element WHERE id_floor='$floor'";
//         $query = "SELECT  element.id, symbol.link_photo, device.value, symbol_family.is_visible, symbol.value
//FROM symbol
//JOIN symbol_family ON symbol.id_symbol_family = symbol_family.id
//JOIN element ON element.id_symbol_family = symbol.id_symbol_family
//JOIN device ON device.id = element.id_device WHERE symbol.value = device.value AND element.id = '$id'";
//          
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
     function setValueById($id, $value){
         $query = "UPDATE device SET value='$value', set_value=1 WHERE id=(SELECT id_device FROM element WHERE id='$id')";
        $result = mysql_query($query);
        //$row = mysql_fetch_array($result, MYSQL_NUM); 
      //  if(empty($result))
     //   return false;
     //   else
      //      return true;
    }    
    
    function prepareValueElementById($id){
        $query = "UPDATE device SET get_value='1' WHERE id=(SELECT id_device FROM element WHERE id='$id')";
        $result = mysql_query($query);
        return $result;
      
    }
    
    function selectTypeById($id){
        $query = "SELECT is_visible FROM symbol_family WHERE id =(SELECT id_symbol_family FROM element WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        $is_visible = $row[0];    
        return $is_visible;           
    }
    
    function selectValueElementById($id){
        $query = "SELECT value FROM device WHERE id =(SELECT id_device FROM element WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        $value = $row[0];    
        return $value;        
    }

    
    function selectPhotoByElementByIdAndValue($id, $value){
        $query = "SELECT link_photo FROM symbol WHERE value='$value' and id_symbol_family = (SELECT id_symbol_family FROM element WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        $photo = $row[0];    //photo
        if($photo == ""){
            $query = "SELECT link_photo FROM symbol WHERE value='-1' and id_symbol_family = (SELECT id_symbol_family FROM element WHERE id='$id')";
            $result = mysql_query($query);
            $ret_res = mysql_num_rows($result);
            $row = mysql_fetch_array($result, MYSQL_NUM);
            $photo = $row[0];    //photo  
        }
            
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