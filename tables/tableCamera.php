<?php

require_once('connectDb.php');
$connectDB = new connectDb();

class tableCamera {

    function select() {
        $query = "SELECT * FROM camera ORDER BY data DESC LIMIT 0,1";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        $camera = "";
        if ($ret_res) {
            while ($row = mysql_fetch_row($result)) {                
                $camera[$licznik][0] = $row[0];    //id
                $camera[$licznik][1] = $row[1];    //frame
                $camera[$licznik][2] = $row[2];    //data
                $licznik++;
            }            
            return $camera;
        }
    }

}

?>
