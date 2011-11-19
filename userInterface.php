<?php

/**
 * UserInterface - interfejs użytkownika
 */
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
        if (!isset($_SESSION['privileges']))
            $_SESSION['privileges'] = 0;
        $this->user_privileges= $_SESSION['privileges'];
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

    function getPrivileges(){
        
        return $this->privileges;
    }
    
    function show($title, $jquery, $headerTitle, $menu, $content, $image, $min_privileges) {

//im wyzsze tym wiecej mozna... trzeba wydetywoac troche grupy uzytkownikow...
//mozna to pozniej okroic do dwoch ale spoko, tak jest lepiej mniej pisania


        if (getPrivileges() >= $min_privileges) {
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
        foreach ($menu as $title => $content) {
            echo '<div class="title1">' . $title . '</div>';
            echo '<div class="text1">';
            echo $content;
            echo '</div>';
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

        $links = '<li><a href="symbol_family.php?action=add">Panel administracyjny</a></li>    
        <li><a href="index.php">Logi zdarzeń</a></li>
        <li><a href="index.php">Kamera</a></li>';

        return array('Kondygnacje' => $floors, 'Wejścia' => $input, 'Linki' => $links);
    }

    /**
     * Zwraca menu dla panelu administracyjnego
     * @return type 
     */
    function leftMenuAdminPanel() {
        $symbols = '<li><a href="symbol_family.php">Grupy symboli</a></li>
        <li><a href="index.php">Symbole</a></li>';

        $devices = '<li><a href="index.php">Urządzenie</a></li>
        <li><a href="index.php">Wejścia</a></li>';

        $elements = '<li><a href="index.php">Kondygnacje</a></li>
        <li><a href="index.php">Elementy wizualizacji</a></li>';

        $administration = '<li><a href="index.php">Użytkownicy</a></li>
        <li><a href="index.php">Logi</a></li>';

        $links = '<li><a href="index.php">Strona główna</a></li>
        <li><a href="index.php">Logi zdarzeń</a></li>
        <li><a href="index.php">Kamera</a></li>';

        return array('Symbole' => $symbols, 'Urządzenia' => $devices, 'Elementy wizualizacji' => $elements, 'Administracja' => $administration, 'Linki' => $links);
    }

}

?>
