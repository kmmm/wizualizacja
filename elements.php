<?php

/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */
require_once 'userInterface.php';
require_once 'tables/tableSymbolFamily.php';
require_once 'tables/tableDevice.php';
require_once 'tables/tableFloor.php';
require_once 'tables/tableSymbol.php';

$userInterface = new userInterface();
$tableSymbolFamily = new tableSymbolFamily();
$tableSymbol = new tableSymbol();
$tableDevice = new tableDevice();
$tableFloor = new tableFloor();


if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie elementami wizualizacji";

    $jquery = '<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
    
var v;
    
    $("#main").delegate("", "click", function()
    {
         v++;
        if(v==2){
           $("#main").onClick(findPos(document.getElementById(\'main\')));                                              
        }
    });

    $("#main").delegate("#position", "click", function()
    {
        v=0;
        $("#center").hide();
	$("#main").css({\'background-image\' : \'url("photo/floor75f1a23038d404cafd5bb14f1bd78b1687fc0b8f.jpg")\'}); 
    });




    $("#text3").delegate("#select_symbolfamily_delete", "change", function()
    {
        var id= $("#select_symbolfamily_delete").val();
	$("#text3").load("ajaxSymbol.php?id_delete="+id);
    });
    
    $("#text3").delegate("#select_symbol", "change", function()
    {
        var id= $("#select_symbol").val();
	$("#text3").load("ajaxSymbol.php?id_symbol="+id);
    });
});
</script>


<script>
    // Detect if the browser is IE or not.
    // If it is not IE, we assume that the browser is NS.
    var IE = document.all?true:false

    // If NS -- that is, !IE -- then set up for mouse capture
    if (!IE) document.captureEvents(Event.MOUSEMOVE)
    // Set-up to use getMouseXY function onMouseMove
    document.onmousemove = getMouseXY;

    // Temporary variables to hold mouse x-y pos.s
    var tempX = 0
    var tempY = 0

    // Main function to retrieve mouse x-y pos.s
    function getMouseXY(e) {
    if (IE)
    {
        // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft
        tempY = event.clientY + document.body.scrollTop
    }
    else
    {
        // grab the x-y pos.s if browser is N
        tempX = e.pageX
        tempY = e.pageY
    }
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0}
    if (tempY < 0){tempY = 0}
    return true;
    }
      //-->

    //w zmiennych tempX i tempY sa trzymane aktualne współrzędne kursora
    var pos_left = "";
    var pos_top = "";

    function findPos(obj) {
    var nleft = 0;
    var ntop = 0;
    if (obj.offsetParent)
    {
        nleft = obj.offsetLeft
        ntop = obj.offsetTop
        while (obj = obj.offsetParent)
        {
            nleft += obj.offsetLeft
            ntop += obj.offsetTop
        }
    }

   pos_left = nleft;
   pos_top = ntop;
   divx=tempX-pos_left;
   divy=tempY-pos_top;
   jQuery("#posx").val(divx);
   jQuery("#posy").val(divy);   
   $("#main").css({\'background-image\' : \'url("")\'}); 
   $("#center").show();
   return true;
   }

//funkcja zwraca stringa z współrzędnymi z położeniem diva przekazanego w obiekcie obj
</script>
';

    $headerTitle = "Panel administracyjny - zarządzanie elementami wizualizacji";
    $divBackground = null;
    $alert = null;

    $minUserPrivleges = '2';

    $link = '<li><a href="elements.php?action=add">Dodaj element</a></li>
             <li><a href="elements.php?action=delete">Usuń element</a></li>';


    /*
     * Kod strony
     */
//$_POST['send'] ma polskie wartości tylko dlatego, że IE8 to najgłupsza przeglądarka pod słońcem i zastanawiam się
//jak to się dzieje, że ludzie jeszcze jej używają. 
    if (isset($_POST['send'])) {
        switch ($_POST['send']) {
            case 'dodaj':
                if (isset($_FILES['img']['tmp_name']) && isset($_POST['value']) && isset($_POST['select_symbolfamily'])) {
                    if (($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg")) {
                        $number = rand(1, 10000);
                        $plik_ext = explode('.', $_FILES['img']['name']);
                        do {
                            $nazwa = sha1(date("d.m.Y.H.i.s") . $plik_ext[0] . $number) . '.' . $plik_ext[1];
                        } while (file_exists($nazwa));
                        $ret = $tableSymbol->instert($_POST['select_symbolfamily'], "photo/" . $nazwa, $_POST['value'], 1);
                        $alert = $ret[1];
                        if ($ret[0] == 1) {
                            if (is_uploaded_file($_FILES['img']['tmp_name'])) {
                                move_uploaded_file($_FILES['img']['tmp_name'], "photo/$nazwa");
                            } else {
                                $alert = 'Nie udało się wgrać pliku na serwer';
                            }
                        }
                    } else {
                        $alert = 'Niepoprawnie format obrazków.';
                    }
                } else {
                    $alert = 'Niepoprawnie wybrane obrazki.';
                }
                break;
            case 'usuń':
                if ($_POST['select_symbol'] != null) {
                    $symbol = $tableSymbol->selectRecordById($_POST['select_symbol']);
                    $ret = $tableSymbol->delete($_POST['select_symbol']);
                    $alert = $ret[1];
                    if ($ret[0] == 1) {
                        unlink('./' . $symbol[2]);
                    }
                } else {
                    $alert = 'Niepoprawnie wypełnione pola!';
                }
                break;
            default:
                break;
        }
    }

    switch ($_GET['action']) {
        case 'add':
            $devices = $tableDevice->selectAllRecords();
            $floors = $tableFloor->selectAllRecords();
            $symbolsFamily = $tableSymbolFamily->selectAllRecords();
            $symbols = $tableSymbol->selectAllRecords();
            if (!empty($floors)) {
                if (!empty($symbolsFamily)) {
                    if (!empty($symbols)) {
                        if (!empty($devices)) {
                            $form = '<form action="elements.php?action=add" method="POST">
                                    <table>
                                    <tr>
                                        <td>Nazwa elementu:</td>
                                        <td><input type="text" id="name" name="name" /></td>                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kondygnacja: </td>
                                        <td><select id="floor" name="floor">';
                            foreach ($floors as $floor) {
                                $form.='<option value ="' . $floor[0] . '">' . $floor[1] . '</option>';
                            }
                            $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Grupa symboli: </td>
                                        <td><select id="symbolfamily" name="symbolfamily">
                                            <option>---</option>';
                            foreach ($symbolsFamily as $symbolFamily) {
                                $form.='<option value ="' . $symbolFamily[0] . '">' . $symbolFamily[1] . '</option>';
                            }
                            $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Urządzenie: </td>
                                        <td><select id="device" name="device">
                                            <option>---</option>';
                            foreach ($devices as $device) {
                                $form.='<option value ="' . $device[0] . '">'. $device[1] .' '.$device[2].'</option>';
                            }
                            $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Położenie na wizualizacji:</td>
                                        <td><input type="text" id="posx" name="posx" size=1/>,
                                        <input type="text" id="posy" name="posy" size=1/>
                                        <input type="button" id="position" name="position" value="wybierz pozycję"></td>
                                    </tr>
                                    <tr><td colspan=2><button type="submit" id="send" name="send" value="dodaj" onclick="this.value=add">dodaj</button></td></tr>
                                    </table>
                                    </form>';
                        } else {
                            $form = '<h3>Baza danych nie zawiera żandych urządzeń.</h3>';
                        }
                    } else {
                        $form = '<h3>Baza danych nie zawiera żandych symboli.</h3>';
                    }
                } else {
                    $form = '<h3>Baza danych nie zawiera żandych grup symboli.</h3>';
                }
            } else {
                $form = '<h3>Baza danych nie zawiera żandych kondygnacji.</h3>';
            }
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Dodaj element', $alert);
            break;
        case 'delete':
            $symbolFamily = $tableSymbolFamily->selectAllRecords();
            if (!empty($symbolFamily)) {
                $form = '<form action="symbol.php?action=delete" method="POST">
                    <table>
                    <tr>
                        <td>Grupa: </td>
                        <td><select id="select_symbolfamily_delete" name="select_symbolfamily_delete">
                        <option>---</option>';
                foreach ($symbolFamily as $symbol) {
                    $form.='<option value ="' . $symbol[0] . '">' . $symbol[1] . '</option>';
                }
                $form.='</select></td>
                    </tr>
                    </table>
                 </form>';
            } else {
                $form = '<h3>Baza danych nie zawiera żandych grup symboli.</h3>';
            } $content = $userInterface->adminPanelFormFrame($link, $form, 'Usuń symbol', $alert);
            break;
        default:
            $minUserPrivleges = x;
            break;
    }

    $menu = $userInterface->leftMenuAdminPanel();

    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
