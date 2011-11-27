<?php

require_once('connectDb.php');
$connectDB = new connectDb();

/*
 * Klasa z funkcjami zapytań dla tabeli symbol
 */

class tableInputs {
    
    
    function getValueById($id){
         $query = "SELECT value FROM device WHERE id = (SELECT id_device FROM checkbox_list WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);        
        return $row[0];
    }
        function setValueById($id, $value){
         $query = "UPDATE device SET set_value=1, value='$value' WHERE id=(SELECT id_device FROM checkbox_list WHERE id='$id')";
        $result = mysql_query($query);
        //$row = mysql_fetch_array($result, MYSQL_NUM); 
      //  if(empty($result))
     //   return false;
     //   else
      //      return true;
    }
    
    
    
    
    
    function selectRecordByName($name){
         $query = "SELECT * FROM checkbox_list WHERE name='$name'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);        
        return $row;
    }
    function instert($name, $id_device, $active) {
        $row = $this->selectRecordByName($name);
        if (empty($row)) {
            $query = "INSERT INTO checkbox_list values ('','$id_device', '$name','$active')";
            $result = mysql_query($query);
            if ($result) {
                return 'Dodano nowy wejście!';
            } else {
                return 'Nie udało dodać się nowego wejścia :(.';

            }
        } else {
            return 'W bazie istnieje już takie wejście!';

        }
    }
        function update($id, $name, $id_device, $active) {
        $row = $this->selectRecordByName($id);
        if (empty($row) || $row[0] == $id) {
            $query = "UPDATE checkbox_list SET  id_device='$id_device', name='$name', active='$active' WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Wyedytowano wejście';
            } else {
                return 'Nie udało się wyedytować wejścia.';
            }
        } else {
            return 'W bazie nie istnieje takie wejście!';
        }
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM checkbox_list WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        $checkbox;
        $row = mysql_fetch_array($result, MYSQL_NUM);
            $checkbox['id'] = $row[0];    //id
            $checkbox['id_device'] = $row[1];    //id_device
            $checkbox['name'] = $row[2];    //name
            $checkbox['active'] = $row[3];    //active

        
        return $checkbox;
    }

    function selectAllRecords(){
        $query = "SELECT * FROM checkbox_list";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        $checkbox;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $checkbox[$licznik]['id'] = $row[0];    //id
            $checkbox[$licznik]['id_device'] = $row[1];    //id_device
            $checkbox[$licznik]['name'] = $row[2];    //name
            $checkbox[$licznik]['active'] = $row[3];    //active
            $licznik++;
        }
        return $checkbox;
    
    }
    

    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM checkbox_list WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                    return 'Usunięto wejście!';

            } else {
    return 'Nie udało się usunąć wejścia.';

            }
        } else {
    return 'W bazie nie ma takiego wejścia!';

        }
    }

}

?>
