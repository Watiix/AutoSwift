<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Nom prenom : Nicastro Luca, Mohamed Shaco
* Projet : Api AutoSwift
* Date : Mars 2024
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once "../Models/dao.php";
require_once "../Models/util.php";

$_SERVER['REQUEST_METHOD'] = 'POST';
error_log($_SERVER['REQUEST_METHOD']);

$bodyRaw = file_get_contents("php://input");
$body = json_decode($bodyRaw, true);
$token = getTokenDB();

// Creation d'annonces

// Verifie si la key exists dans le body
if (
    !array_key_exists("description", $body) ||
    !array_key_exists("carburant", $body) ||
    !array_key_exists("kilometrage", $body) ||
    !array_key_exists("prix", $body) ||
    !array_key_exists("date", $body) ||
    !array_key_exists("puissance", $body) ||
    !array_key_exists("boitevitesse", $body) ||
    !array_key_exists("consommation", $body) ||
    !array_key_exists("images", $body) ||
    !array_key_exists("modele", $body)
) {
    returnError(400, ["message" => "ERROR_KEY_UNKNOWN"]);
    die;
}

$description = filter_var($body["description"], FILTER_SANITIZE_STRING);
$carburant = filter_var($body["carburant"], FILTER_SANITIZE_STRING);
$kilometrage = filter_var($body["kilometrage"], FILTER_SANITIZE_NUMBER_INT);
$prix = filter_var($body["prix"], FILTER_SANITIZE_NUMBER_INT);
$date = filter_var($body["date"], FILTER_SANITIZE_STRING);
$puissance = filter_var($body["puissance"], FILTER_SANITIZE_NUMBER_INT);
$boitevitesse = filter_var($body["boitevitesse"], FILTER_SANITIZE_STRING);
$consommation = filter_var($body["consommation"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$images = filter_var($body["images"], FILTER_SANITIZE_STRING);
$modele = filter_var($body["modele"], FILTER_SANITIZE_NUMBER_INT);

// Verifie si le prix est supérieur à 0
if($prix < 0)
{
    returnError(400, ["message"=>"ERROR_NEGATIVE_AMOUNT"]);
    die;
}

// Verifie si le token dans le body est le même que celui dans la base
if($token !== getToken())
{
    returnError(400, ["message"=>"ERROR_TOKEN_INVALID"]);
    die;
}

// Cree l'annonce avec tous les paramètres
createAdd($description, $carburant, $kilometrage, $prix, $date, $puissance, $boitevitesse, $consommation, $images, $modele);
http_response_code(201);
returnError(201, ["status" => "AD_CREATED_SUCCESSFULLY"]);

