<?php

/**
 * UserInterface - interfejs użytkownika
 */
require_once 'connectDb.php';
require_once 'tables/tableInputs.php';

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
    public $jquery = "";
    public $title = "";
    public $content = "";
    public $headerTitle = "";
    public $menu = "";
    public $image = "";
    public $min_privileges = "";

    public function __construct() {

        $connectionWithDb = new connectDb();
        if (!isset($_SESSION['privileges'])) {
            $_SESSION['privileges'] = 0;
        }
        $this->user_privileges = $_SESSION['privileges'];
    }

    public function logout() {
        $_SESSION['privileges'] = 0;
        $this->user_privileges = 0;
        session_destroy();
    }

    public function login() {
        if (isset($_POST['login']) && isset($_POST['haslo'])) {
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            $query = 'SELECT user_type FROM user WHERE login = "' . $login . '" AND password = "' . $haslo . '"';
            $result = mysql_query($query);
            $ret_res = mysql_num_rows($result);
            $row = mysql_fetch_array($result, MYSQL_NUM);
            if (empty($row)) {
                $_SESSION['privileges'] = 0;
                $this->title = "wizualizacja";
                $this->headerTitle = "Logowanie";
                $this->content.= "<h3>Złe dane logowania!</h3>";
                $this->user_privileges = 0;
            } else {
                $_SESSION['login'] = $login;
                $_SESSION['privileges'] = $row[0];
                $this->user_privileges = $row[0];
            }
        } else {
            $this->content.= '<table class="center">
        <tr><td colspan=2>Podaj swoje dane do logowania:</td></tr>
        <form action="index.php" method="post"><div>
        <tr><td><h4>Login:</h4></td>
        <td><input type="text" name="login"></td></tr>
        <tr><td><h4>Hasło:</h4></td>
        <td><input type="password" name="haslo"></td></tr>
        <tr><td colspan=2><button type="submit" id="wyślij" name="wyślij" value="zaloguj"/>zalgouj</button></td></tr>
        </table>
        </form>';
        }
    }

//    public function login() {
//        $userInterface = new userInterface();
//        $content = null;
//        if ($this->user_privileges == 0) {
//            if (isset($_POST['login']) && isset($_POST['haslo'])) {
//                $login = $_POST['login'];
//                $haslo = $_POST['haslo'];
//                $query = 'SELECT user_type FROM user WHERE login = "' . $login . '" AND password = "' . $haslo . '"';
//                $result = mysql_query($query);
//                $ret_res = mysql_num_rows($result);
//                $row = mysql_fetch_array($result, MYSQL_NUM);
//                if (empty($row)) {
//                    $_SESSION['privileges'] = 0;
//
//                    $title = "wizualizacja";
//                    $this->jquery = '<script>
//                     function init(){
//				setTimeout(\'document.location="index.php"\', 2000);
//			}
//		window.onload=init;
//                </script>';
//                    $headerTitle = "Błąd logowania";
//                    $content = "<h3>Złe dane logowania!</h3>";
//
//                    $user_privileges = 0;
//                    $menu = null;
//                    $divBackground = null;
//                    $userInterface->show($title, $this->jquery, $headerTitle, $menu, $content, $divBackground, $user_privileges);
//                    return false;
//                } else {
//
//                    $_SESSION['login'] = $login;
//                    $_SESSION['privileges'] = $row[0];
//                    $this->user_privileges = $row[0];
//                    return true;
//                }
//            } else {
//                $content = '<html><head><title> Logowanie </title>
//        <meta name="keywords" content="wizualizacja">
//        <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
//        <meta http-equiv="Content-Language" content="pl">
//        <link rel="stylesheet" href="visualizationStyle.css" type="text/css"/></head>
//        <body>
//        <div id="header">
//        <div id="header_inner">
//        <h1> Logowanie </h1>
//        </div>
//        </div>
//        <div id="loginmain">
//        <table class="center">
//        <tr><td colspan=2>Podaj swoje dane do logowania:</td></tr>
//        <form action="index.php" method="post"><div>
//        <tr><td><h4>Login:</h4></td>
//        <td><input type="text" name="login"></td></tr>
//        <tr><td><h4>Hasło:</h4></td>
//        <td><input type="password" name="haslo"></td></tr>
//        <tr><td colspan=2><button type="submit" id="wyślij" name="wyślij" value="zaloguj"/>zalgouj</button></td></tr>
//        </table>
//        </form>
//        </div>
//        <div id="footer">
//        <br>Wizualizacja domu by Kinga Makowiecka and Michał Marasz
//        </div>
//        </body>
//        </html>';
//                echo $content;
//                return false;
//            }
//        } else
//            return true;
//    }
//
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

        </body>
        </html>'; //        <div id="footer">

//        <br>Wizualizacja domu by Kinga Makowiecka and Michał Marasz
//        </div>

    function getPrivileges() {

        return $this->user_privileges;
    }

//
//    function show($title, $jquery, $headerTitle, $menu, $content, $image, $min_privileges) {
//        $this->jquery.=$jquery;
////im wyzsze tym wiecej mozna... trzeba wydetywoac troche grupy uzytkownikow...
////mozna to pozniej okroic do dwoch ale spoko, tak jest lepiej mniej pisania
//
//
//        if ($this->getPrivileges() >= $min_privileges) {
//            echo '<html>';
//            echo '<head>';
//            echo '<title>' . $title . '</title>';
//            echo '<meta name="keywords" content="wizualizacja">';
//            echo '<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>';
//            echo '<meta http-equiv="Content-Language" content="pl">';
//            echo '<link rel="stylesheet" href="visualizationStyle.css" type="text/css"/>';
//            echo $this->jquery;
//            echo '</head>';
//            echo '<body>';
//            echo $this->header($headerTitle);
//            if ($_SESSION['privileges'] > 0) {
//                echo $this->leftmenu($menu);
//            }
//            echo $this->main($content, $image);
//            echo $this->footer();
//            echo '</body>';
//            echo '</html>';
//        } else {
//            echo $this->not_allowed;
//        }
//    }



    function show($title, $jquery, $headerTitle, $menu, $content, $image, $min_privileges) {  
        if ($this->getPrivileges() >= $min_privile)
        $this->jquery.=$jquery;
        echo '<html>';
        echo '<head>';
        echo '<title>' . $title . '</title>';
        echo '<meta name="keywords" content="wizualizacja">';
        echo '<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>';
        echo '<meta http-equiv="Content-Language" content="pl">';
        echo '<link rel="stylesheet" href="visualizationStyle.css" type="text/css"/>';
        echo $this->jquery;
        echo '</head>';
        echo '<body>';
        if ($this->getPrivileges() >= $min_privileges) {
            echo $this->header($headerTitle);
            echo $this->leftmenu($menu);
            $idDiv = 'main';
        } else {
            if ($_SESSION['privileges'] > 0) {
                echo $this->not_allowed;
            } else {
                $this->login();
            }
        }
        echo $this->main($content, $image, $idDiv);
        echo $this->footer();
        echo '</body>';
        echo '</html>';
    }

    /**
     * Div nagłówka
     * @param type $headerTitle 
     */
    function header($headerTitle) {
        echo '<div id="header">';
        echo '<div>';
        if ($_SESSION['privileges'] > 0)
            echo 'Zalogowano jako: ' . $_SESSION['login'] . ' :: <a class="login" href="changePass.php" >Zmiana hasła</a> :: <a class="login" href="loginOutUser.php">Wyloguj</a>';
        echo '</div>';
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
//    function main($content, $image) {
//        echo '<div id="main" style=\'background-image: url("' . $image . '")\'>';
//        echo $content;
//        echo '</div>';
//    }

    function main($content, $image, $idDiv) {
        echo '<div id="' . $idDiv . '" style=\'background-image: url("' . $image . '")\'>';
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

    /**
     *
     * @return lewe menu dla wizualizacji
     */
    function leftMenuIndex() {
        require_once './tables/tableFloor.php';
        $tableFloor = new tableFloor();
        $floor = $tableFloor->selectAllRecords();
        $floors = "";
        if (!empty($floor)) {
            foreach ($floor as $f) {
                $floors.= '<li><a href="index.php?floor=' . $f[1] . '">Piętro ' . $f[1] . '</a></li>';
            }
        } else {
            $floors = 'Brak kondygnacji';
        }

        $this->jquery = '<script type="text/javascript" src="jquery.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){ ';

        $tableInputs = new tableInputs();
        $inputs = $tableInputs->selectAllRecords();
        $inputForm = '';
        //$inputForm = '<form id="inputs" action ="index.php">';
        if (!empty($inputs)) {
            foreach ($inputs as $input) {
                //var_dump($input['name']) or die();
                //$(\'.'.$input['id'].'\').load("ajaxInputs.php?get_id='.$input['id'].'&name='.$input['name'].'");
                $this->jquery.='$(\'.' . $input['id'] . '\').change(function(){
                            $(\'.' . $input['id'] . '\').load("ajaxInputs.php?get_id=' . $input['id'] . '");  
                            });';
                if ($tableInputs->getValueById($input['id']) == 1)
                    $inputForm.='<div class="' . $input['id'] . '"><input type="checkbox" checked="yes" div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
                else
                    $inputForm.='<div class="' . $input['id'] . '"><input type="checkbox"  div="' . $input['id'] . '"">' . $input['name'] . '</input><br></div>';
            }
        } else
            $inputForm.='Brak zdefiniowanych wejść';
        //$inputForm.='</from>';
        $this->jquery.='});</script>';

        $links = "";
        if ($this->user_privileges > 1) {
            $links = '<li><a href="symbol_family.php?action=add">Panel administracyjny</a></li>';
        }
        $links.= '<li><a href="index.php">Kamera</a></li>';
        if ($_SERVER['REQUEST_URI'] == "/wizualizacja/changePass.php") {
            $links.= '<li><a href="index.php">Strona główna</a></li>';
        }
        return array('Kondygnacje' => $floors, 'Wejścia' => $inputForm, 'Linki' => $links);
    }

    /**
     * Zwraca menu dla panelu administracyjnego
     * @return type 
     */
    function leftMenuAdminPanel() {
        $symbols = '<li><a href="symbol_family.php?action=add">Grupy symboli</a></li>
        <li><a href="symbol.php?action=add">Symbole</a></li>';

        $devices = '<li><a href="device.php?action=add">Urządzenia</a></li>';

        $elements = '<li><a href="floor.php?action=add">Kondygnacje</a></li>
        <li><a href="Inputs.php?action=add">Wejścia</a></li>
        <li><a href="elements.php?action=add">Elementy na wizualizacji</a></li>';

        $administration = '<li><a href="user.php?action=add">Użytkownicy</a></li>';

        $links = '<li><a href="index.php">Strona główna</a></li>
        <li><a href="index.php">Kamera</a></li>';

        return array('Symbole' => $symbols, 'Urządzenia' => $devices, 'Elementy wizualizacji' => $elements, 'Administracja' => $administration, 'Linki' => $links);
    }

    /* Ramka formularza w admice - ta z linkami */

    function adminPanelFormFrame($link, $form, $divTitle, $alert) {
        $content = '
            <div class="center" id="center">
                <div class="title2">' . $divTitle . '</div>
                <div class="text2">
                    <div class="center">';
        if ($link != "") {
            $content.='<div class="text3" id="text3link">
                        ' . $link . '
                        </div>';
        }
        $content.='<div class="text3" id="text3">
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
