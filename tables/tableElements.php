<?php


class tableElements {
    
    
 function insert($name, $id_device, $id_symbol_family, $id_floor, $position_x, $position_y, $active) {
        $row = $this->selectRecord($name, $id_device, $id_symbol_family, $id_floor, $position_x, $position_y, $active);
        if (empty($row)) {
            $query = "INSERT INTO element values ('','$name', '$id_device', '$id_symbol_family', '$id_floor', '$position_x', '$position_y', '$active')";
            $result = mysql_query($query);
            if ($result) {
                return 'Dodano nowy element na wizualizację!';
            } else {
                return 'Nie udało dodać się nowego elementu na wizualizację.';
            }
        } else {
            return 'W bazie istnieje już taki element!';
        }
    }

    function selectRecord($name, $id_device, $id_symbol_family, $id_floor, $position_x, $position_y, $active) {
        $query = "SELECT * FROM element WHERE name='$name' and id_device='$id_device' and id_symbol_family='$id_symbol_family' and id_floor='$id_floor' and position_x='$position_x' and position_y='$position_y' and active='$activenumber='$number' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }
}

?>
