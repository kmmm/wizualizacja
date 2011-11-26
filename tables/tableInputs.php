<?php
require_once('connectDb.php');
$connectDB = new connectDb();



function getInputsFromBase(){

        $query = "SELECT * FROM checkbox_list WHERE active=1";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $return[$licznik]['id'] = $row[0];    
            $return[$licznik]['name'] = $row[2];    

            $licznik++;
        }
        return $return;
    
}

function getAllId(){

    $query = "SELECT id FROM checkbox_list WHERE active=1";
    $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $return[$licznik]['id'] = $row[0];    
            $licznik++;
        }
        return $return;
}

function getValueById($id){
     $query = "SELECT * FROM device WHERE id =(SELECT id_device FROM checkbox_list WHERE id='$id')";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);

        return $row[3];
}
    
?>
