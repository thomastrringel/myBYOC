<?php

  // 01.12.2022
  // http://subdomain.thomas-ringel.de/mb/1IdentifyUser.php?service=3
  // ?service=1..4 definiert welcher service aufgefrufen werden soll
  if(isset($_GET['service'])) {
    $service = $_GET['service'];
  } else {
    $service = 1; // default
  }

  if(isset($_GET['debug'])) {
    // debug Cookie schon bekannt im System => tue nichts
    ;
  } else {
      // setze Cookie debug=FALSE, wenn es noch keinen gesetzten Cookie debug gibt => sonst kommt später eine Fehlermeldung wegen eines nicht gesetzten Cookies
      setcookie("debug", "FALSE", time()+3600, "/mb/", "");
  }

  echo ("service=" . $service);

    // In order to initiate the end user’s authorization, you must redirect the end user’s browser to our authorize endpoint. 
    // This will provide a login screen to the end user for authentication. After successful authentication, the consent screen is displayed, 
    // if the user has not given the consent yet.

    // response_type=code: Request an authorization code as the result of the end user authorization process.
    // client_id=<insert_your_client_id_here>: Provide the client ID of your application.
    // redirect_uri=<insert_redirect_uri_here>: This is the callback URL that is registered for you application in order to receive the authorization code.
    // scope=<insert_scopes_of_API_here>: Include the scopes for the API, which are the permissions to request the end users consent for. 
    //      For each API, you can find the required scopes in the additional API specific documentation. 
    //      Please note that you will have to request an additional scope "offline_access" to receive a refresh token.
    // prompt=<insert_desired_behavior_here>: This is an optional parameter containing a space delimited, case sensitive list of predefined values 
    //      (login, consent) that specify whether the authorization server prompts the end user for re-authentication and/or consent.
    // state=<insert_client_state_here>: An opaque value used by the client to maintain state between the request and callback. 
    //      The authorization server includes this value when redirecting the user-agent back to the client. 
    //      The parameter SHOULD be used for preventing cross-site request forgery.

    $scope = Array();
    $scope[1] = 'openid offline_access mb:vehicle:mbdata:vehiclestatus'; // 3GET_VehicleLockStatus.php
    $scope[2] = 'openid offline_access mb:vehicle:mbdata:payasyoudrive'; // 3GET_PayAsYouDrive.php
    $scope[3] = 'openid offline_access mb:vehicle:mbdata:fuelstatus'; 
    $scope[4] = 'openid offline_access mb:vehicle:mbdata:evstatus';
    $scope[5] = 'openid offline_access mb:vehicle:mbdata:vehiclelock';    

    $state = Array();
    $state[1] = 'vehiclestatus';
    $state[2] = 'payasyoudrive';
    $state[3] = 'fuelstatus'; 
    $state[4] = 'electricvehicle';
    $state[5] = 'vehiclelockstatus';

    // $service = 4; // welche Dienst willst du abfragen?

    $response_type = 'code';
    $client_id = 'putyourclientidhere';    
    $redirect_uri = 'https://subdomain.thomas-ringel.de/mb/2GetAuthorizationCode.php';    
    
    // $prompt = 'login consent';
    // $code = 'code';   
    // $client_secret = 'BukeroOJuSVpZpyPLsdDpckBuKvqwlGcBUVCRBiaNhkddSGrBEgrfifyHBKKbSZf'; // no valid client_secret
    // https://www.base64encode.org/
    // $clientIdSecret64 = 'MDFlM2Y3ZjYtOTc5ZC00Y2FhLWJkNDItNzZjMTIzZThhNDI5OkJ1a2Vyb09KdVNWcFpweVBMc2REcGNrQnVLdnF3bEdjQlVWQ1JCaWFOaGtkZFNHckJFZ3JmaWZ5SEJLS2JTWmY='; 

    $fields = http_build_query(array(
        "response_type" => $response_type,
        "client_id" => $client_id,
        "redirect_uri" => $redirect_uri,
        "scope" => $scope[$service],
        "state" => $state[$service]
      ));

    echo ("<br>1 = vehiclestatus");
    echo ("<br>2 = payasyoudrive");    
    echo ("<br>3 = fuelstatus");  
    echo ("<br>4 = evstatus");
    echo ("<br>5 = vehiclelockstatus");
    echo ("<br>?service=" . $service);
    echo ("<br>");

    // Auth URL
    $link = 'https://ssoalpha.dvb.corpinter.net/v1/auth?' . $fields;
    echo ("<p> <a href=\"" . $link . "\" target=\"_blank\"> STARTE AUTHENTIFIZIERUNG CONNECT ME </a></p>");

?>
