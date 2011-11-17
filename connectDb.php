<?php

class connectDb {
    public function __construct() {
        $serwer='localhost';
        $user='root';
        $haslo='haslo';
        $db='visualisation';
        
        mysql_connect($serwer, $user, $haslo)or die("Nie można nawiązać połączenia z bazą"); //Łączenie z bazą danych
        mysql_select_db($db)or die("Wystąpił błąd podczas wybierania bazy danych"); //Wybieranie konkretnej bazy danych
        mysql_query("SET NAMES 'utf8'");
    }
}

?>
