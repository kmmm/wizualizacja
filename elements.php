<?php
session_start();
/**
 * symbol_family - formularze zarządzania rodzinami symboli
 */
require_once 'userInterface.php';
require_once 'tables/tableSymbolFamily.php';
require_once 'tables/tableDevice.php';
require_once 'tables/tableFloor.php';
require_once 'tables/tableSymbol.php';
require_once 'tables/tableElements.php';

$userInterface = new userInterface();
$tableSymbolFamily = new tableSymbolFamily();
$tableSymbol = new tableSymbol();
$tableDevice = new tableDevice();
$tableFloor = new tableFloor();
$tableElements = new tableElements();


$devices = $tableDevice->selectAllRecords();
$floors = $tableFloor->selectAllRecords();
$symbolsFamily = $tableSymbolFamily->selectAllRecords();
$symbols = $tableSymbol->selectAllRecords();

if ($userInterface->login()) {
    $title = "Panel administracyjny - zarządzanie elementami wizualizacji";

    $jquery = '<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){    
    
var v;
        var floorid = $("#floor").val();
        $.get("ajaxElements.php?floorid="+floorid, function(result) {
        $("#i").val(result);           
    });   


    
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
        var img = $("#i").val();
        $("#center").hide();
	$("#main").css({\'background-image\' : \'url("\'+img+\'")\'}); 
    });

    $("#text3").delegate("#symbolfamily", "change", function()
    {
            var id= $("#symbolfamily").val();        
	$("#device").load("ajaxElements.php?id="+id);
    });
    
    $("#text3").delegate("#floor", "change", function()
    {
        var floorid = $("#floor").val();
        $.get("ajaxElements.php?floorid="+floorid, function(result) {
            $("#i").val(result);           
        });   
    });
    
    $("#text3").delegate("#element", "change", function()
    {
            var id= $("#element").val();        
	$("#text3").load("ajaxElements.php?id_element="+id);
    });
    
    $("#text3").delegate("#element_delete", "change", function()
    {
            var id= $("#element_delete").val();        
	$("#text3").load("ajaxElements.php?id_element_delete="+id);
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
             <li><a href="elements.php?action=edit">Edytuj element</a></li>
             <li><a href="elements.php?action=delete">Usuń element</a></li>';


    /*
     * Kod strony
     */
//$_POST['send'] ma polskie wartości tylko dlatego, że IE8 to najgłupsza przeglądarka pod słońcem i zastanawiam się
//jak to się dzieje, że ludzie jeszcze jej używają. 
    if (isset($_POST['send'])) {
        switch ($_POST['send']) {
            case 'dodaj':
                if (($_POST['name']) != "" && $_POST['floor'] != "" && $_POST['symbolfamily'] != "" && $_POST['device'] != "" && $_POST['posx'] != "" && $_POST['posy'] != "") {
                    $alert = $tableElements->insert($_POST['name'], $_POST['device'], $_POST['symbolfamily'], $_POST['floor'], $_POST['posx'], $_POST['posy'], "1");
                } else {
                    $alert = 'Niepoprawnie wypełnione pola.';
                }
                break;
            case 'edytuj':
                if ($_POST['element'] != "" && $_POST['name'] != "" && $_POST['floor'] != "" && $_POST['symbolfamily'] != "" && $_POST['device'] != "" && $_POST['posx'] != "" && $_POST['posy'] != "") {
                    $alert = $tableElements->update($_POST['element'], $_POST['name'], $_POST['device'], $_POST['symbolfamily'], $_POST['floor'], $_POST['posx'], $_POST['posy'], "1");
                } else {
                    $alert = 'Niepoprawnie wypełnione pola.';
                }
                break;
            case 'usuń':
                if ($_POST['element_delete'] != "") {
                    $alert = $tableElements->delete($_POST['element_delete']);
                } else {
                    $alert = 'Niepoprawnie wypełnione pola.';
                }
                break;
            default:
                break;
        }
    }

    switch ($_GET['action']) {
        case 'add':
            if (!empty($floors)) {
                if (!empty($symbolsFamily)) {
                    if (!empty($symbols)) {
                        if (!empty($devices)) {
                            $form = '<form action="elements.php?action=add" method="POST">
                                    <table>
                                    <tr>
                                        <td>Nazwa elementu:</td>
                                        <td><input type="text" id="name" name="name" /></td>
                                    </tr>
                                    <tr>
                                        <td>Kondygnacja: </td>
                                        <td><select id="floor" name="floor">';
                            foreach ($floors as $floor) {
                                $form.='<option value ="' . $floor[0] . '">' . $floor[1] . '</option>';
                            }
                            $form.='</select>';
                            $form.='<input type="hidden" id="i" name="i"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grupa symboli: </td>
                                        <td><select id="symbolfamily" name="symbolfamily">
                                            <option value="">---</option>';
                            foreach ($symbolsFamily as $symbolFamily) {
                                $form.='<option value ="' . $symbolFamily[0] . '">' . $symbolFamily[1] . '</option>';
                            }
                            $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Urządzenie: </td>
                                        <td><select id="device" name="device">
                                            <option value="">---</option>';
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
        case 'edit':
            $elements = $tableElements->selectAllRecords();
            if (!empty($elements)) {
                if (!empty($floors)) {
                    if (!empty($symbolsFamily)) {
                        if (!empty($symbols)) {
                            if (!empty($devices)) {
                                $form = '<form action="elements.php?action=edit" method="POST">
                                    <table>
                                    <tr>
                                    <td>
                                    Element:
                                    </td>
                                    <td><select id="element" name="element">
                                    <option value="">---</option>';
                                foreach ($elements as $element) {
                                    $form.='<option value ="' . $element[0] . '">' . $element[1] . '</option>';
                                }
                                $form.='</td>
                                    </tr>
                                    <tr>
                                        <td>Nazwa elementu:</td>
                                        <td><input type="text" id="name" name="name" disabled=disabled/></td>
                                    </tr>
                                    <tr>
                                        <td>Kondygnacja: </td>
                                        <td><select id="floor" name="floor" disabled=disabled>';
                                foreach ($floors as $floor) {
                                    $form.='<option value ="' . $floor[0] . '">' . $floor[1] . '</option>';
                                }
                                $form.='</select>';
                                $form.='<input type="hidden" id="i" name="i"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grupa symboli: </td>
                                        <td><select id="symbolfamily" name="symbolfamily" disabled=disabled>
                                            <option value="">---</option>';
                                foreach ($symbolsFamily as $symbolFamily) {
                                    $form.='<option value ="' . $symbolFamily[0] . '">' . $symbolFamily[1] . '</option>';
                                }
                                $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Urządzenie: </td>
                                        <td><select id="device" name="device" disabled=disabled>
                                            <option value="">---</option>';
                                $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Położenie na wizualizacji:</td>
                                        <td><input type="text" id="posx" name="posx" size=1/ disabled=disabled>,
                                        <input type="text" id="posy" name="posy" size=1/ disabled=disabled>
                                        <input type="button" id="position" name="position" value="wybierz pozycję" disabled=disabled></td>
                                    </tr>
                                    <tr><td colspan=2><button type="submit" id="send" name="send" value="edytuj" onclick="this.value=add" disabled=disabled>edytuj</button></td></tr>
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
            } else {
                $form = '<h3>Baza danych nie zawiera żandych elementów.</h3>';
            }
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Edytuj element', $alert);
            break;
        case 'delete':
            $elements = $tableElements->selectAllRecords();
            if (!empty($elements)) {
                if (!empty($floors)) {
                    if (!empty($symbolsFamily)) {
                        if (!empty($symbols)) {
                            if (!empty($devices)) {
                                $form = '<form action="elements.php?action=delete" method="POST">
                                    <table>
                                    <tr>
                                    <td>
                                    Element:
                                    </td>
                                    <td><select id="element_delete" name="element_delete">
                                    <option value="">---</option>';
                                foreach ($elements as $element) {
                                    $form.='<option value ="' . $element[0] . '">' . $element[1] . '</option>';
                                }
                                $form.='</td>
                                    </tr>
                                    <tr>
                                        <td>Nazwa elementu:</td>
                                        <td><input type="text" id="name" name="name" disabled=disabled/></td>
                                    </tr>
                                    <tr>
                                        <td>Kondygnacja: </td>
                                        <td><select id="floor" name="floor" disabled=disabled>';
                                foreach ($floors as $floor) {
                                    $form.='<option value ="' . $floor[0] . '">' . $floor[1] . '</option>';
                                }
                                $form.='</select>';
                                $form.='<input type="hidden" id="i" name="i"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grupa symboli: </td>
                                        <td><select id="symbolfamily" name="symbolfamily" disabled=disabled>
                                            <option value="">---</option>';
                                foreach ($symbolsFamily as $symbolFamily) {
                                    $form.='<option value ="' . $symbolFamily[0] . '">' . $symbolFamily[1] . '</option>';
                                }
                                $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Urządzenie: </td>
                                        <td><select id="device" name="device" disabled=disabled>
                                            <option value="">---</option>';
                                $form.='</select></td>
                                    </tr>
                                    <tr>
                                        <td>Położenie na wizualizacji:</td>
                                        <td><input type="text" id="posx" name="posx" size=1/ disabled=disabled>,
                                        <input type="text" id="posy" name="posy" size=1/ disabled=disabled>
                                        <input type="button" id="position" name="position" value="wybierz pozycję" disabled=disabled></td>
                                    </tr>
                                    <tr><td colspan=2><button type="submit" id="send" name="send" value="usuń" disabled=disabled>usuń</button></td></tr>
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
            } else {
                $form = '<h3>Baza danych nie zawiera żandych elementów.</h3>';
            }
            $content = $userInterface->adminPanelFormFrame($link, $form, 'Usuń element', $alert);
            break;
        default:
            $minUserPrivleges = x;
            break;
    }

    $menu = $userInterface->leftMenuAdminPanel();

    $userInterface->show($title, $jquery, $headerTitle, $menu, $content, $divBackground, $minUserPrivleges);
}
?>
