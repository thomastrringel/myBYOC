<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Plotly.js -->
	<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>

<body>





<?php

  $ValidPreCon = 0; // zähle die erfüllten Voraussetzungen einzeln
  $NeededPreCon = 3; // wie viele Voraussetzungen müssen erfüllt sein?
  // prüfe, ob die VIN als Cookie gesetzt ist
  if( isset($_COOKIE["vin"])) {
      $vin = $_COOKIE["vin"]; 
      $ValidPreCon = $ValidPreCon + 1;
      // echo "vin found=" . $vin . "<br />";
    } else {
      echo "Sorry... vin Not recognized" . "<br />";

  }

  // prüfe ob der AccessToken vorhanden ist
  if( isset($_COOKIE["BYOCAR_ACCESSTOKEN"])) {
    $AccessToken = $_COOKIE["BYOCAR_ACCESSTOKEN"];   
    $ValidPreCon = $ValidPreCon + 1;  
    // echo "AccessToken found=" . $AccessToken . "<br />";
    }  else {
    echo "Sorry... AccessToken Not recognized" . "<br />";
  
  }

  // prüfe ob der state gesetzt ist (beschreibt welcher Dienst abgefragt wurde)
  if( isset($_COOKIE["state"])) {
    $state = $_COOKIE["state"];   
    $ValidPreCon = $ValidPreCon + 1;  
    // echo "state found=" . $state . "<br />";
    }  else {
    echo "Sorry... State Not recognized" . "<br />";
  
  }

  // definiere Variablen, die man später anzeigen will

  // service vehiclestatus
  $sunroofstatus = 9;  // 9 = Data not available
  $windowstatusfrontleft = 9;  // 9 = Data not available  
  // service payasyoudrive
  $odo = 0;  
  // service fuelstatus
  $rangeliquid = 0;
  $tanklevelpercent = 0;
  // service evstatus
  $soc = 0;
  $rangeelectric = 0;
  // service vehiclelockstatus
  $positionheading = 999;
  $doorlockstatusdecklid = 2;
  $doorlockstatusgas;
  $doorlockstatusvehicle = 4;


  // führe die API Abfrage durch (ohne Auswertung => Rückgabe ist 'false' oder ein JSON Array)  
  function getAPIAnswer($api, $access_token, $debug) {

    // führt nur die API Abfrage durch und schaut nach ob ein Fehler aufgetreten ist
    // $api = API Adresse korrekt zusammengesetzt
    // $access_token = gültiger access_token für die API
    // $debug = true und dann werden Debug Infos ausgegeben

    // bereite eine GET Abfrage vor
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    $headers = array();
    $headers[] = 'Accept: application/json;charset=utf-8';
    $headers[] = 'Authorization: Bearer ' . $access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // führe die Abfrage aus
    $result = curl_exec($ch);
    
    // werte die Anwort der API Schnittstelle aus
    if (curl_errno($ch)) {
      // konnte curl_exec nicht ausführen => das wäre ein fataler Fehler
      echo "<br>curl_exec Error - " . curl_error($ch);
      // ??? was mache ich denn dann
    } else {
      // curl_exec konnte ausgeführt werden
      // im $result steht nun das Ergebnis der Abfrage oder eine Fehlermeldung der API; die API hat auf jeden Fall korrekt geantwortet
      if ($debug == true) { echo("<br>curl_exec Result - " . $result); }

      // JSON-Ausgabe in PHP-Array umwandeln
      $result2 = json_decode($result, true);

      // Ausgabe des Arrays
      if ($debug == true) { echo "<pre>"; print_r ($result2); echo "</pre>"; }

      // gab es einen Fehler?
      $pos = strpos($result, 'errorMessage');
      if ($pos === false) {
        if ($debug == true) { echo "The string errorMessage was not found in the string "; }
        ; // tue nichts
      } else {
        echo "<br>There was an errorMessage found<br>";
        // Ausgabe des Fehlers
        echo "<pre>"; print_r ($result2); echo "</pre>"; 
        return "false";
      }
   
    }

    curl_close($ch);

    return $result2;

  }

  // Zeige die Rohdaten
  function showdata($resource, $value, $timestamp) {
    // ISO8601 formatted datetime        
    $mytimestamp = date("d.m.Y - H:i", $timestamp); 
    
    echo "<br>";
    echo "<br>resource is " . $resource . " with value = " . $value;
    echo "<br>DATUM=" . $mytimestamp; //similar to 2021-01-15T02:52:35+00:00 
    echo "<br>";

  }


  // werte das JSON Resultat aus und suche die Keys und Werte und merke dir diese
  function getKeys($value, $key) {

    // diese Werte will ich mir merken für später
    // service vehiclestatus
    global $sunroofstatus;
    global $windowstatusfrontleft;  
    // service payasyoudrive
    global $odo;  
    // service fuelstatus
    global $rangeliquid;
    global $tanklevelpercent;
    // service evstatus
    global $soc;
    global $rangeelectric;
    // service vehiclelockstatus
    global $positionheading;
    global $doorlockstatusdecklid;
    global $doorlockstatusgas;
    global $doorlockstatusvehicle;


    // echo("<br>getKeys=$key: $value ");
    // echo("<br>resource=" . $key);
    // echo("<br>value=" . $value['value']);
    // echo("<br>timestamp=" . $value['timestamp']);   
    $myresource = $key;
    $myvalue = $value['value']; 
    // get timestamp und wandele es in lesbares Format um und schneide die letzten drei Nullen ab - BYOCAR API liefert '1648225446000'
    $mytimestamp = substr($value['timestamp'], 0, 10);
 
    // zeige die Rohdaten an
    showdata($myresource, $myvalue, $mytimestamp);

    // merke dir die Werte für die Anzeige in Plotly oder HTML später
    if ( strcmp("sunroofstatus", $myresource) == 0 ) { $sunroofstatus = $myvalue; };    
    if ( strcmp("windowstatusfrontleft", $myresource) == 0 ) { $windowstatusfrontleft = $myvalue; };   
    
    if ( strcmp("odo", $myresource) == 0 ) { $odo = $myvalue; };  
    
    if ( strcmp("rangeliquid", $myresource) == 0 ) { $rangeliquid = $myvalue; };
    if ( strcmp("tanklevelpercent", $myresource) == 0 ) { $tanklevelpercent = $myvalue; };

    if ( strcmp("soc", $myresource) == 0 ) { $soc = $myvalue; };
    if ( strcmp("rangeelectric", $myresource) == 0 ) { $rangeelectric = $myvalue; };

    if ( strcmp("positionHeading", $myresource) == 0 ) { $positionheading = $myvalue; };
    if ( strcmp("doorlockstatusdecklid", $myresource) == 0 ) { $doorlockstatusdecklid = $myvalue; };
    if ( strcmp("doorlockstatusgas", $myresource) == 0 ) { $doorlockstatusgas = $myvalue; };
    if ( strcmp("doorlockstatusvehicle", $myresource) == 0 ) { $doorlockstatusvehicle = $myvalue; };


  }


  function getVehicleStatus($result) {
    // wertet eine korrekte API Abfrage aus

    // das $result sieht ungefähr so aus
    // das Problem ist, dann man nicht immer sicher weiß, ib ein bestimmter Key (z. B. readingLampFrontLeft) auch wirklich gesendet wurde
    // man muss also zunächst rausfinden, welche Keys in dem $result vorhanden sind
    //
    // Array <= das ist $result
    //  (
    //    [0] => Array
    //      (
    //        [readingLampFrontLeft] => Array
    //            (
    //                [value] => false
    //                [timestamp] => 1648289766000
    //            )
    //
    //      )
    //
    //    [1] => Array
    //      (
    //        [interiorLightsFront] => Array
    //            (
    //                [value] => false
    //                [timestamp] => 1648289766000
    //            )
    //
    //      )
    // ...

    // gehe alle Werte durch
    $count = count($result);
    for($i=0; $i < $count; $i++) {

    // echo print_r(array_keys($result[$i]));

    array_walk($result[$i], 'getKeys');

  }

  
}


if ($ValidPreCon == $NeededPreCon) {
  // Voraussetzungen erfüllt => Abfragen können gemacht werden

  $myOnCarAPIContainers = 'https://api.mercedes-benz.com/vehicledata/v2/vehicles/' . $vin . '/containers/' . $state;
  // $myOnCarAPIContainers = 'https://api.mercedes-benz.com/vehicledata/v2/vehicles/' . $vin . '/resources';
  echo $myOnCarAPIContainers;
  $result = getAPIAnswer($myOnCarAPIContainers, $AccessToken, true);
  
  // gab es einen Fehler => wenn ein gültiges Array zurückkommt, dann war alles OK
  // bei einem Fehler kommt nur der Text "false" zurück
  if (is_array($result)) {
    // API hatte offenbar keinen Fehler => Anzeige kann erfolgen
    getVehicleStatus($result);      
  }
    
  } else {
  echo ("<br> Voraussetzungen nicht erfüllt für eine Abfrage. AccessToken fehlt.");
}


?>



<div id="resRangeLiquid" style="width:90%;height:400px;"></div>
<div id="resTanklevelPercent" style="width:90%;height:400px;"></div>
<div id="resSoc" style="width:90%;height:400px;"></div>
<div id="resRangeElectric" style="width:90%;height:400px;"></div>
<div id="resVehicleStatus_SunRoofStatus" style="width:90%;height:200px;"></div>
<div id="resVehicleStatus_Windowstatusfrontleft" style="width:90%;height:200px;"></div>

<script>

// übernehme Variable von PHP - muss vorher definiert sein
var RANGELIQUID = <?php echo json_encode($rangeliquid); ?>; 
var TANKLEVELPERCENT = <?php echo json_encode($tanklevelpercent); ?>; 
var SOC = <?php echo json_encode($soc); ?>; 
var RANGEELECTRIC = <?php echo json_encode($rangeelectric); ?>; 
var VEHICLESTATUS_SUNROOFSTATUS = <?php echo json_encode($sunroofstatus); ?>; 
var VEHICLESTATUS_WINDOWSTATUSFRONTLEFT = <?php echo json_encode($windowstatusfrontleft); ?>; 
var VEHICLESLOCKTATUS_POSITIONHEADING = <?php echo json_encode($positionheading); ?>; 
var VEHICLELOCKSTATUS_DOORLOCKSTATUSDECKLID = <?php echo json_encode($doorlockstatusdecklid); ?>;
var VEHICLELOCKSTATUS_DOORLOCKSTATUSGAS = <?php echo json_encode($doorlockstatusgas); ?>;
var VEHICLELOCKSTATUS_DOORLOCKSTATUSVEHICLE = <?php echo json_encode($doorlockstatusvehicle); ?>;


</script>

<?php include("resRangeLiquid.php"); ?>

<?php include("resTanklevelPercent.php"); ?>

<?php include("resSoc.php"); ?>

<?php include("resRangeElectric.php"); ?>

<?php include("resVehicleStatus_SunRoofStatus.php"); ?>

<?php include("resVehicleStatus_WindowStatusFrontLeft.php"); ?>

<?php include("resVehicleLockStatus_positionHeading.php"); ?>

<?php include("resVehicleLockStatus_doorlockstatusdecklid.php"); ?>

<?php include("resVehicleLockStatus_doorlockstatusgas.php"); ?>

<?php include("resVehicleLockStatus_doorlockstatusvehicle.php"); ?>


</body>

</html>
