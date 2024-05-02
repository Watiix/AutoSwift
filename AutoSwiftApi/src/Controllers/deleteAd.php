<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Nom prenom : Nicastro Luca, Mohamed Shaco
* Projet : Api AutoSwift
* Date : Mars 2024
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once "../Models/dao.php";
require_once "../Models/util.php";

$_SERVER['REQUEST_METHOD'] = 'DELETE';
error_log($_SERVER['REQUEST_METHOD']);

$bodyRaw = file_get_contents("php://input");
$body = json_decode($bodyRaw, true);
$token = getTokenDB();

// Delete Annonces

// Verifie si la key exists dans le body
if (
    !array_key_exists("id", $body)
){
    returnError(400, ["message" => "ERROR_KEY_UNKNOWN"]);
    die;
}

$idAdd = filter_var($body["id"], FILTER_SANITIZE_NUMBER_INT);

// Récupére l'id du user pour l'annonce correspondante
$idUser = getIdUserAd($idAdd); 

// Verifie si la variable est vide
if (empty($idAdd)) {
    returnError(400, ["message" => "ERROR_VARIABLES_REQUIRED_CANNOT_BE_NULL"]);
    die;
}

if (!$idAdd) {
    returnError(400, ["message"=>"ERROR_INVALID_ADD_ID"]);
    die;
}

// Verifie si l'annonce existe grâce à son id 
if(!adExist($idAdd))
{
    returnError(400, ["message"=>"ERROR_AD_NOT_FOUND"]);
    die;
}

// Verifie si la key exists dans le body
if(getTokenById($idUser) !== getToken())
{
    returnError(400, ["message"=>"ERROR_TOKEN_INVALID"]);
    die;
}

deleteAdd($idAdd);
http_response_code(201);
returnError(201, ["status" => "AD_DELETED_SUCCESSFULLY"]);
