<?php

require_once 'userInterface.php';

$userInterface = new userInterface();
$content;
if ($userInterface->login()) {
    if (isset($_POST['old_pass']) && isset($_POST['new_pass1']) && isset($_POST['new_pass2'])) {

        if($_POST['new_pass1']!="" && $_POST['new_pass2']!="" && $_POST['old_pass']!=""){
        if ($_POST['new_pass1'] == $_POST['new_pass2']) {
            $login = $_SESSION['login'];
            $new_pass = $_POST['new_pass1'];
            $old_pass = $_POST['old_pass'];

            $query = "SELECT * FROM user WHERE login = '$login' AND password ='$old_pass'";
            $result = mysql_query($query);
            $ret_res = mysql_num_rows($result);
            $row = mysql_fetch_array($result, MYSQL_NUM);
            if (empty($row)) {

                $content = '<h3>Podano niepoprawne stare hasło.</h3>';
            } else {
                //poprawnie - mozna zmieniac
                $query1 = "UPDATE user SET password ='$new_pass' WHERE login='$login'";

                $result1 = mysql_query($query1);
                if (empty($result1))
                    $content = '<h3>Niestety, nie udało się zaktualizować hasła.</h3>';
                else
                    $content = '<h3>Hasło zaktualizowano poprawnie!</h3>';
            }
        } else {
            $content = '<h3>Nowe hasła różnią się.</h3>';
        }} else 
            $content = '<h3>Proszę uzupełnić wszystkie pola!</h3>';
    } else {
        $content = '<h2>Zmiana hasła</h2>
        <form action="changePass.php" method="post"><div>
        <h4>Stare hasło:</h4>
        <input type="password" name="old_pass">
        <h4>Nowe hasło:</h4>
        <input type="password" name="new_pass1" id="new_pass1"></br>
        <h4>Powtórz nowe hasło:</h4>
        <input type="password" name="new_pass2" id="new_pass2"></br>
        <input type="submit" name="wyślij">
        </div></form>';
    }
    echo '<html><head><title> Zmiana hasła </title>
        <meta name="keywords" content="wizualizacja">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="visualizationStyle.css" type="text/css"/></head>
        <body>
        <div id="header">
        <div id="header_inner">
        <h1> Logowanie </h1>
        </div>
        </div>
        <div id="main">';
    echo $content;
    echo'</div>
        <div id="footer">
        <br>Wizualizacja domu by Kinga Makowiecka and Michał Marasz
        </div>
        </body>
        </html>';
}
?>