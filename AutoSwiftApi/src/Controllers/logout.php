<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Nom prenom : Nicastro Luca, Mohamed Shaco
* Projet : Api AutoSwift
* Date : Mars 2024
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once "../Models/dao.php";
require_once "../Models/util.php";

$token = getTokenDB();

// si le token est le même supprime le token dans la base
if($token !== getToken())
{
    returnError(400, ["message"=>"ERROR_TOKEN_INVALID"]);
    die;
}
deleteToken();
returnError(201, ["status" => "SUCCES_LOGOUT"]);