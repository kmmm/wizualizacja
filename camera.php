<?php

	$host = "localhost";		//adres serwera sql
	$db_user = "root";		//uzytkownik bazy danych sql
	$db_password = "haslo";	//hasło bazy danych
	$database = "visualisation";			//tablica bazy danych
	
mysql_connect($host, $db_user, $db_password) or die('Błąd połączenia z bazą danych!');
mysql_select_db($database) or die('Nie udało się wybrać bazy danych!');
  
        $rezultat = mysql_query("SELECT * FROM camera ORDER BY data DESC");
      if (@mysql_num_rows($rezultat)) {
	  while ($wiersz = mysql_fetch_row($rezultat)) {

	  
	  
	  
	  
	  
	  
	  
	  header('Content-type: image/jpeg');
	  
	  echo base64_decode ($wiersz[1]);
      }}



?>