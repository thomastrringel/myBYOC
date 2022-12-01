<<?php

// 01.12.2022
// ACHTUNG => ICH WILL MIR DEN ACCESSTOKEN ALS COOKIE SPEICHERN
// DAHER DARF VOR DEM SPEICHERN DES COOKIES KEINE AUSGABE PER ECHO ETC. ERFOLGEN
setcookie("vin", "W1K2130111B006842", time()+3600, "/mb/", ""); // speichere $vin als Cookie für 1h speichern - nur zu Testzwecken (ohne Funktion)

// prüfe ob debugging informationen angezeigt werden sollen 
$debug = $_COOKIE["debug"]; // wenn ein Cookie debug=true gesetzt ist, dann werden Debugdaten ausgegeben

/* Übernehme Übergabevariable von der BYOCAR API */
$AuthorizationCode = $_GET["code"];
$state = $_GET["state"];
$access_token = $_GET["access_token"];

// setze einen Cookie welcher Dienst angefragt wurde => kommt von 1IdentifyUser.php
setcookie("state", $state, time()+3600, "/mb/", ""); // speichere $state als Cookie für 1h speichern - merke dir für welchen Dienst der Token erstellt wurde

// definiere Variablen zur Erzeugung des Access Tokens
$client_id = '165329f9-8347-4f65-b5ae-92260f6c0e16';
$client_secret = 'ESmqXwBwhfvlPTjrLougiDxMRMYAKrPBiprtZnfIcovtWPKFDbGFtHRDBdnDsjvr';
$redirect_uri = 'https://subdomain.thomas-ringel.de/mb/2GetAuthorizationCode.php';

// Base64 encoding is a way of encoding binary data into text so that it can be easily transmitted across a network without error. 
// No matter which service you use, ensure that no spaces are appended to the CLIENT_ID and CLIENT_SECRET keys and 
// separate the CLIENT_ID and CLIENT_SECRET with a colon, i.e. CLIENT_ID:CLIENT_SECRET.
$clientIdSecret64 = "Authorization: Basic " . base64_encode($client_id . ':' . $client_secret);

// jetzt muss man sich mit dem AuthorizationCode einen AccessToken holen
function getAccessToken() {

    if (strcmp($debug, 'TRUE') == 0) {echo "<br>DEBUG=TRUE";}

    // übernehme die Variablen in die Funktion
    global $debug;
    global $AuthorizationCode;
    global $redirect_uri;
    global $clientIdSecret64;

    if (strcmp($debug, 'TRUE') == 0) {echo "<br>debug=" . $debug;}
    if (strcmp($debug, 'TRUE') == 0) {echo "<br>AuthorizationCode=" . $AuthorizationCode;}
    if (strcmp($debug, 'TRUE') == 0) {echo "<br>redirect_uri=" . $redirect_uri;}
    if (strcmp($debug, 'TRUE') == 0) {echo "<br>clientIdSecret64=" . $clientIdSecret64;}
   
    // definiere die HTTP POST Abfrage gemäß BYOCAR API Spezifikation
    $ch = curl_init();

    $data = "grant_type=authorization_code&code=" . $AuthorizationCode . "&redirect_uri=" . $redirect_uri;
    if (strcmp($debug, 'TRUE') == 0) {echo "<br>data=" . $data;}

    // Token URL
    curl_setopt($ch, CURLOPT_URL, 'https://ssoalpha.dvb.corpinter.net/v1/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $headers = array();
    $headers[] = $clientIdSecret64;
    $headers[] = 'content-type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // führe HTTP POST aus
    $response = curl_exec($ch);
    if (strcmp($debug, 'TRUE') == 0) {echo "<br>response=" . $response;}

    // ist ein Fehler aufgetreten?
    $err = curl_error($ch);

    // schließe den Abfragekanal
    curl_close($ch);

    // werte den response aus
    if ($err) {
        // es gab einen HTTP POST Error
        echo "<br>ERR-01 cURL Error: " . $err;
    } else {
        // die API hat zumindest geantwortet
        $response = json_decode($response, true);

        // wenn es den Key access_token gibt, dann wurde ein access_token generiert und alles war OK
        if(array_key_exists("access_token", $response)) {
            // speichere den AccessToken als Cookie für 1h speichern
            setcookie("BYOCAR_ACCESSTOKEN", $response['access_token'], time()+3600, "/mb/", "");
            // und beende die Routine
            return $response;
        } 

        // wenn der Key error exisitiert, dann hat die API einen Fehler zurückgemeldet 
        if(array_key_exists("error", $response)) echo ("<br>ERR-02 " . $response["error_description"]);
        echo "<br>ERR-03 Something went extremely wrong! Please contact admin.";

    }

    // wenn man bis hierher kommt gab es einen Fehler
    return 'ERROR';

} 

$response = getAccessToken(); // !!! die Variable wird weiterverwendet

// Ausgabe erst am Ende möglich, damit der Cookie access_token erfolgreich gesetzt werden kann
// falls debug=true, dann geben die Ergebnisse der BYOCAR API aus
if(strcmp($debug, 'TRUE') == 0) {
    echo ("<br>INPUT FROM 1.IdentifyUser.php redirected to 2GetAuthorizationCode.php");
    echo ("<br>-CODE=" . $AuthorizationCode);
    echo ("<br>-STATE=" . $state);
    echo ("<br>-access_token=" . $access_token);
    echo ('<br>FINAL API RESPONSE' . $resonse);
    echo ('<br>access_token: ' . $response["access_token"] );
    echo ('<br>refresh_token: ' . $response["refresh_token"] );    
    echo ('<br>token_type: ' . $response["token_type"] );
    echo ('<br>expires_in: ' . $response["expires_in"] );
}

// das wird auf jeden Fall ausgegeben
echo ("<br>" . $response . "<br>");

$link = 'https://subdomain.thomas-ringel.de/mb/3GET_AllData.php';
echo ("<p> <a href=\"" . $link . "\" target=\"_blank\"> STARTE DATENABFRAGE BYOCAR </a></p>");


?>