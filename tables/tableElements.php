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
        $query = "SELECT * FROM element WHERE name='$name' and id_device='$id_device' and id_symbol_family='$id_symbol_family' and id_floor='$id_floor' and position_x='$position_x' and position_y='$position_y' and active='$active'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectRecordById($id) {
        $query = "SELECT * FROM element WHERE id='$id'";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        return $row;
    }

    function selectAllRecords() {
        $query = "SELECT * FROM element WHERE active=1 ORDER by name ASC";
        $result = mysql_query($query);
        $ret_res = mysql_num_rows($result);
        $licznik = 0;
        $element="";
        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $element[$licznik][0] = $row[0];    //id
            $element[$licznik][1] = $row[1];    //name
            $element[$licznik][2] = $row[2];    //id_device
            $element[$licznik][3] = $row[3];    //id_symbol_family
            $element[$licznik][4] = $row[4];    //id_floor
            $element[$licznik][5] = $row[5];    //pos_x
            $element[$licznik][6] = $row[6];    //pos_y
            $element[$licznik][7] = $row[7];    //active            
            $licznik++;
        }
        return $element;
    }

    function update($id, $name, $id_device, $id_symbol_family, $id_floor, $position_x, $position_y, $active) {
        $row = $this->selectRecord($name, $id_device, $id_symbol_family, $id_floor, $position_x, $position_y, $active);
        if (empty($row) || $row[0] == $id) {
            $query = "UPDATE element SET name='$name', id_device='$id_device', id_symbol_family='$id_symbol_family',
            id_floor='$id_floor', position_x='$position_x', position_y='$position_y', active='$active'
            WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Wyedytowano element!';
            } else {
                return 'Nie udało się wyedytować elementu.';
            }
        } else {
            return 'W bazie istnieje już taki element!';
        }
    }

    
    function delete($id) {
        $row = $this->selectRecordById($id);
        if (!empty($row)) {
            $query = "DELETE FROM element WHERE id='$id'";
            $result = mysql_query($query);
            if ($result) {
                return 'Usunięto element!';
            } else {
                return 'Nie udało się usunąć elementu.';
            }
        } else {
            return 'W bazie nie ma takiego elementu!';
        }
    }
}

?>
