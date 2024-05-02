<?php

function returnResponse($response) {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// function pour return les erreurs et code d'erreur
function returnError($errorCode, $response) {
    http_response_code($errorCode);
    returnResponse($response);
}