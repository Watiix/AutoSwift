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

// Creation de compte

// Verifie si la key exists dans le body
if (
    !array_key_exists("email", $body) ||
    !array_key_exists("password", $body) ||
    !array_key_exists("lastname", $body) ||
    !array_key_exists("firstname", $body) ||
    !array_key_exists("birthdate", $body) ||
    !array_key_exists("address", $body)
) {
    returnError(400, ["message" => "ERROR_KEY_UNKNOWN"]);
    die;
}

$email = filter_var($body["email"], FILTER_VALIDATE_EMAIL);
$password = filter_var($body["password"], FILTER_SANITIZE_STRING);
$lastname = filter_var($body["lastname"], FILTER_SANITIZE_STRING);
$firstname = filter_var($body["firstname"], FILTER_SANITIZE_STRING);
$birthdate = filter_var($body["birthdate"], FILTER_SANITIZE_STRING);
$address = filter_var($body["address"], FILTER_SANITIZE_STRING);

if (!$email) {
    returnError(400, ["message" => "ERROR_EMAIL_UNKNOWN"]);   
    die;
}

// Verifie le password est plus grand que 5
if (strlen($password) < 5) {
    returnError(400, ["message" => "ERROR_PWD_SHORT"]);
    die;
}

// Verifie une des variables n'est pas vide
if (empty($email) || empty($password) || empty($lastname) || empty($firstname) || empty($birthdate) || empty($address)) {
    returnError(400, ["message" => "ERROR_VARIABLES_REQUIRED_CANNOT_BE_NULL"]);
    die;
}

// Verifie si l'email est deja utilisé
if(emailAlreadyExists($email))
{
    returnError(400, ["message"=>"ERROR_EMAIL_ALREADY_USED"]);
    die;
}

// Genère un passwordHash et un token
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$token = md5(uniqid($password));

// Cree le compte 
createUser($firstname, $lastname, $address, $birthdate, $email, $passwordHash, $token);
returnError(201, ["message"=>"compte cree"]);
getAllUsers();

http_response_code(201);
returnResponse(["token" => $token]);
