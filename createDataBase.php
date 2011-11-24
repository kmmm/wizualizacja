<?php

//$serwer = 'localhost';
//$user = 'root';
//$haslo = 'mdrp180';

$serwer = 'localhost';
$user = 'root';
$haslo = 'haslo';


mysql_connect($serwer, $user, $haslo) or die("Nie można nawiązać połączenia z bazą"); //Łączenie z bazą danych

$create_database = "CREATE DATABASE visualisation";
$result = mysql_query($create_database);
if ($result) {
    echo 'Utworzono bazę danych.<br>';
}
$db = 'visualisation';
mysql_select_db($db) or die("Wystąpił błąd podczas wybierania bazy danych"); //Wybieranie konkretnej bazy danych
mysql_query("SET NAMES 'utf8'");


$create_userType = "CREATE TABLE user_type(
id INT NOT NULL AUTO_INCREMENT,
type VARCHAR (60) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
active INT NOT NULL,
PRIMARY KEY (id))
ENGINE=INNODB;";

$result = mysql_query($create_userType);
if ($result) {
    echo 'Utworzono tabelę user_type.<br>';
}

$create_user = "CREATE TABLE user(
id INT NOT NULL AUTO_INCREMENT,
login VARCHAR (60) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
password VARCHAR (32) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
id_user_type INT NOT NULL,
active INT NOT NULL,
PRIMARY KEY (id),
INDEX par_ind_user (id_user_type),
FOREIGN KEY (id_user_type) REFERENCES user_type (id)
ON DELETE CASCADE)
ENGINE=INNODB;";

$result = mysql_query($create_user);
if ($result) {
    echo 'Utworzono tabelę user.<br>';
}

$create_camera = "CREATE TABLE camera(
id INT NOT NULL AUTO_INCREMENT,
frame LONGTEXT CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
data DATE NOT NULL,
PRIMARY KEY (id))
ENGINE=INNODB;";
$result = mysql_query($create_camera);
if ($result) {
    echo 'Utworzono tabelę camera.<br>';
}


$create_floor = "CREATE TABLE floor(
id INT NOT NULL AUTO_INCREMENT,
number INT NOT NULL,
link_photo VARCHAR (100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
active INT NOT NULL,
PRIMARY KEY (id))
ENGINE=INNODB;";
$result = mysql_query($create_floor);
if ($result) {
    echo 'Utworzono tabelę floor.<br>';
}

$create_symbol_family = "CREATE TABLE symbol_family(
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR (100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
is_visible BOOLEAN,
active INT NOT NULL,
PRIMARY KEY (id))
ENGINE=INNODB;";
$result = mysql_query($create_symbol_family);
if ($result) {
    echo 'Utworzono tabelę symbol_family.<br>';
}

$create_symbol = "CREATE TABLE symbol(
id INT NOT NULL AUTO_INCREMENT,
id_symbol_family INT NOT NULL,
link_photo VARCHAR (100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
value INT NOT NULL,
active INT NOT NULL,
PRIMARY KEY (id),
INDEX par_ind_symbol (id_symbol_family),
FOREIGN KEY (id_symbol_family) REFERENCES symbol_family (id)
ON DELETE CASCADE)
ENGINE=INNODB;";

$result = mysql_query($create_symbol);
if ($result) {
    echo 'Utworzono tabelę symbol.<br>';
}


$create_device = "CREATE TABLE device(
id INT NOT NULL AUTO_INCREMENT,
port INT NOT NULL,
type VARCHAR (10) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
value DOUBLE NOT NULL,
get_value BOOLEAN,
set_value BOOLEAN,
PRIMARY KEY (id))
ENGINE=INNODB;";

$result = mysql_query($create_device);
if ($result) {
    echo 'Utworzono tabelę device.<br>';
}

$create_element = "CREATE TABLE element(
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR (100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
id_device INT NOT NULL,
id_symbol_family INT NOT NULL,
id_floor INT NOT NULL,
position_x INT NOT NULL,
position_y INT NOT NULL,
active INT NOT NULL,
PRIMARY KEY (id),
INDEX par_ind_device (id_device),
FOREIGN KEY (id_device) REFERENCES device (id)
ON DELETE CASCADE,
INDEX par_ind_symbol_family (id_symbol_family),
FOREIGN KEY (id_symbol_family) REFERENCES symbol_family (id)
ON DELETE CASCADE,
INDEX par_ind_floor (id_floor),
FOREIGN KEY (id_floor) REFERENCES floor (id)
ON DELETE CASCADE)
ENGINE=INNODB;";
$result = mysql_query($create_element);
if ($result) {
    echo 'Utworzono tabelę element.<br>';
}

$create_checkbox_list = "CREATE TABLE checkbox_list(
id INT NOT NULL AUTO_INCREMENT,
id_device INT NOT NULL,
name VARCHAR (100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
active INT NOT NULL,
PRIMARY KEY (id),
INDEX par_ind_device (id_device),
FOREIGN KEY (id_device) REFERENCES device (id)
ON DELETE CASCADE)
ENGINE=INNODB;";

$result = mysql_query($create_checkbox_list);
if ($result) {
    echo 'Utworzono tabelę checkbox_list.<br>';
}

mkdir('./photo', 0777);

?>
