<?php

/**
 * UserInterface - interfejs użytkownika
 */
require_once 'connectDb.php';

class userInterface {

    /**
     * Wyświetlanie zawartości strony
     * @param type $title
     * @param type $jquery
     * @param type $headerTitle
     * @param type $menu
     * @param type $content
     * @param type $image 
     */
    public function __construct() {
        session_start();
        $connectionWithDb = new connectDb();
        if (!isset($_SESSION['privileges']))
            $_SESSION['privileges'] = 0;
        $this->user_privileges = $_SESSION['privileges'];
    }

    public function logout() {
        $_SESSION['privileges'] = 0;
        $this->user_privileges = 0;
    }

    public function login() {
        $content = null;
        if ($this->user_privileges == 0) {
            if (isset($_POST['login']) && isset($_POST['haslo'])) {

                $login = $_POST['login'];
                $haslo = $_POST['haslo'];

                
            //    $query = 'SELECT user_type.type AS TYPE FROM user_type WHERE id = (SELECT id_user_type FROM user WHERE login = "'.$login.'" AND password = "'.$haslo.'")';
                $query = 'SELECT id_user_type FROM user WHERE login = "'.$login.'" AND password = "'.$haslo.'"';


//                $query = 'SELECT user_type.type AS TYPE FROM user_type WHERE id = (SELECT id_user_type FROM user WHERE login = "' . $login . '" AND password = "' . $haslo . '")';

                $result = mysql_query($query);
                $ret_res = mysql_num_rows($result);
                $row = mysql_fetch_array($result, MYSQL_NUM);
                if (empty($row)) {
                    //      unset($_POST);
                    return false;
                } else {

                    $_SESSION['privileges'] = $row['0'];
                    $this->user_privileges = $row['0'];
                    //  unset($_POST);
                    return true;
                }
            } else {
                $content = '<html><head><title> Logowanie </title>
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
        <div id="main">
        Podaj swoje dane do logowania:
        <form action="index.php" method="post"><div>
        <h4>Login:</h4>
        <input type="text" name="login">
        <h4>Hasło:</h4>
        <input type="password" name="haslo"></br>
        <input type="submit" name="wyślij">
        </div></form>
        </div>
        <div id="footer">
        <br>Wizualizacja domu by Kinga Makowiecka and Michał Marasz
        </div>
        </body>
        </html>';
                echo $content;
                return false;
            }
        } else
            return true;
    }

    private $user_privileges;
    private $not_allowed = '<html><head><title> Brak dostępu do treści </title>
        <meta name="keywords" content="wizualizacja">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="visualizationStyle.css" type="text/css"/></head>
        <body>
        <div id="header">
        <div id="header_inner">
        <h1> Brak dostępu do treści </h1>
        </div>
        </div>
        <div id="main">
        Nie masz praw do oglądania tej treści!
        </div>
        <div id="footer">
        <br>Wizualizacja domu by Kinga Makowiecka and Michał Marasz
        </div>
        </body>
        </html>';

    function getPrivileges() {

        return $this->user_privileges;
    }

    function show($title, $jquery, $headerTitle, $menu, $content, $image, $min_privileges) {

//im wyzsze tym wiecej mozna... trzeba wydetywoac troche grupy uzytkownikow...
//mozna to pozniej okroic do dwoch ale spoko, tak jest lepiej mniej pisania


        if ($this->getPrivileges() >= $min_privileges) {
            echo '<html>';
            echo '<head>';
            echo '<title>' . $title . '</title>';
            echo '<meta name="keywords" content="wizualizacja">';
            echo '<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>';
            echo '<meta http-equiv="Content-Language" content="pl">';
            echo '<link rel="stylesheet" href="visualizationStyle.css" type="text/css"/>';
            echo $jquery;
            echo '</head>';
            echo '<body>';
            echo $this->header($headerTitle);
            if ($_SESSION['privileges'] > 0) {
                echo $this->leftmenu($menu);
            }
            echo $this->main($content, $image);
            echo $this->footer();
            echo '</body>';
            echo '</html>';
        } else {
            echo $this->not_allowed;
        }
    }

    /**
     * Div nagłówka
     * @param type $headerTitle 
     */
    function header($headerTitle) {
        echo '<div id="header">';
        echo '<div id="header_inner">';
        echo '<h1>' . $headerTitle . '</h1>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Div główny
     * @param type $content
     * @param type $image 
     */
    function main($content, $image) {
        echo '<div id="main" style=\'background-image: url("' . $image . '")\'>';
        echo $content;
        echo '</div>';
    }

    /**
     * Div lewego menu
     * @param type $menu 
     */
    function leftmenu($menu) {
        echo '<div id="leftmenu">';
        echo '<div class="left">';
        if ($menu != null) {
            foreach ($menu as $title => $content) {
                echo '<div class="title1">' . $title . '</div>';
                echo '<div class="text1">';
                echo $content;
                echo '</div>';
            }
        }
        echo '</div>';
        echo '</div>';
    }

    /**
     * Div stopki
     */
    function footer() {
        echo '<div id="footer">';
        echo '<br>Wizualizacja domu by Kinga Makowiecka and Michał Marasz';
        echo '</div>';
    }

    function leftMenuIndex() {
        $floors = '<li><a href="index.php">Floor 0</a></li>
        <li><a href="index.php">Floor 1</a></li>';

        $input = '<form action="index.php">
	<input type="checkbox" name="nazwa" value="wartość" />Checkbox1</input><br>
        <input type="checkbox" name="nazwa" value="wartość" />Checkbox2</input><br>
        <input type="checkbox" name="nazwa" value="wartość" />Checkbox3</input><br>
        <input type="checkbox" name="nazwa" value="wartość" />Checkbox4</input><br>
        <input type="checkbox" name="nazwa" value="wartość" />Checkbox5</input>        
        </form>';

        if ($this->user_privileges > 1) {
            $links = '<li><a href="symbol_family.php?action=add">Panel administracyjny</a></li>    
        <li><a href="index.php">Kamera</a></li>
        <li><a href="loginOutUser.php">Wylogowanie</a></li>';
        } else {
            $links = '<li><a href="loginOutUser.php">Wylogowanie</a></li>';

            return array('Kondygnacje' => $floors, 'Wejścia' => $input, 'Linki' => $links);
        }
        return array('Kondygnacje' => $floors, 'Wejścia' => $input, 'Linki' => $links);
    }

    /**
     * Zwraca menu dla panelu administracyjnego
     * @return type 
     */
    function leftMenuAdminPanel() {
        $symbols = '<li><a href="symbol_family.php?action=add">Grupy symboli</a></li>
        <li><a href="symbol.php?action=add">Symbole</a></li>';

        $devices = '<li><a href="index.php">Urządzenie</a></li>
        <li><a href="index.php">Wejścia</a></li>';

        $elements = '<li><a href="index.php">Kondygnacje</a></li>
        <li><a href="index.php">Elementy wizualizacji</a></li>';

        $administration = '<li><a href="user.php?action=edit">Użytkownicy</a></li>';

        $links = '<li><a href="index.php">Strona główna</a></li>
        <li><a href="index.php">Kamera</a></li>';

        return array('Symbole' => $symbols, 'Urządzenia' => $devices, 'Elementy wizualizacji' => $elements, 'Administracja' => $administration, 'Linki' => $links);
    }

    function adminPanelFormFrame($link, $form, $divTitle, $alert) {
        $content = '
            <div class="center">
                <div class="title2">' . $divTitle . '</div>
                <div class="text2">
                    <div class="center">
                        <div class="text3">
                        ' . $link . '
                        </div>
                        <div class="text3" id="text3">
                        <h4>' . $alert . '</h4><br>'
                . $form .
                '</div>
                    </div>
                </div>
            </div>';
        return $content;
    }

}

?>
