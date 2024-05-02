<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Nom prenom : Nicastro Luca, Mohamed Shaco
* Projet : Api AutoSwift
* Date : Mars 2024
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "../Models/dao.php";
require_once "../Models/util.php";

$_SERVER['REQUEST_METHOD'] = 'POST';

$bodyRaw = file_get_contents("php://input");
$body = json_decode($bodyRaw, true);

// Login User

// Verifie si la key exists dans le body
if (
    !array_key_exists("email", $body) ||
    !array_key_exists("password", $body)
){
    returnError(400, ["message" => "ERROR_KEY_UNKNOWN"]);
    die;
}

$email = filter_var($body["email"], FILTER_VALIDATE_EMAIL);
$password = filter_var($body["password"], FILTER_SANITIZE_STRING);

// Récupere le passwordHash
$passwordHash = getHashPassword($email);

// Verifie si les variables sont vides
if (empty($email) || empty($password) || empty($passwordHash)) {
    returnError(400, ["message" => "All.variables.required.cannot.be.null"]);
    die;
}

// essaye de login le user
if(!loginUser($email, $password, $passwordHash))
{
    returnError(400, ["message"=>"ERROR_LOGIN_FAILED"]);
    die;
}   

// génére un nouveau token et l'update dans la base
$token = md5(uniqid($password));
returnResponse(["token" => $token]);
updateToken($token, $email);
returnError(201, ["status" => "SUCCES_CONNECTION"]);