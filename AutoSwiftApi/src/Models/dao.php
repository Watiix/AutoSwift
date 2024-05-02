<?php

require_once "dbinfos.php";
require_once "Databases.php";

// Cree l'utilisateur dans la db
function createUser($firstname, $lastname, $address, $birthdate, $email, $passwordHash, $token) 
{
    $query = "INSERT INTO Utilisateur (Nom, Prenom, Email, MDP, DateNaissance, Adresse, Role_idRole, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = getPDO()->prepare($query);

    $stmt->execute([$lastname, $firstname, $email, $passwordHash, $birthdate, $address, 1, $token]);
}

// Verifie si l'email exsits deja dans la db
function emailAlreadyExists(string $email) :bool
{
    $query = "SELECT Email from Utilisateur WHERE Email = :Email";
    $stmt = getPDO()->prepare($query);

    $stmt->execute([
        ':Email' => $email
    ]);

    return $stmt->rowCount() > 0;      
}

// Récupère le passwordHash dans la db selon l'email 
function getHashPassword($email)
{
    $query = 'SELECT MDP FROM Utilisateur WHERE Email = :Email';
    $stmt = getPDO()->prepare($query);
    $stmt->bindParam(":Email", $email, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch())
    {
        $passwordHash = $row['MDP'];
        return $passwordHash;
    } 
}

// Login l'utilisateur
function loginUser(string $email, string $password, string $passwordHash) :bool
{
    return password_verify($password, $passwordHash);
}

// Récupère tous les Users
function getAllUsers()
{
    $query = 'SELECT * FROM Utilisateur';
    $stmt = getPDO()->prepare($query);
    $stmt->execute();

    returnResponse($row = $stmt->fetchAll());
}

// Crée une annonce
function createAdd($description, $carburant, $kilometrage, $prix, $date, $puissance, $boitevitesse, $consommation, $images, $modele)
{
    $sql = 'SELECT idUtilisateur FROM Utilisateur WHERE token = :token';
    $stmt = getPDO()->prepare($sql);
    $token = getToken();
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(); 

    if ($row) {
        $idUsers = $row['idUtilisateur']; 
        $query = "INSERT INTO Annonce (Description, Carburant, Kilometrage, Prix, Date, Puissance, BoiteVitesse, Consommation, Images, Utilisateur_idUtilisateur, Modele_idModele) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = getPDO()->prepare($query);
    
        $stmt->execute([$description, $carburant, $kilometrage, $prix, $date, $puissance, $boitevitesse, $consommation, $images, $idUsers, $modele]);
    }
    else
    {
        echo "Prblm" . $token;
    }
}

// Supprime une annonce
function deleteAdd($idAdd)
{
    $query = "DELETE FROM Annonce WHERE idAnnonce = ?";
    $stmt = getPDO()->prepare($query);
    $stmt->execute([$idAdd]);
}

// Update le token dans la db
function updateToken(string $token, string $email)
{
    $query = "UPDATE Utilisateur SET token = :token WHERE Email = :Email";
    $stmt = getPDO()->prepare($query);
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->bindParam(":Email", $email, PDO::PARAM_STR);
    $stmt->execute();
}

// Récupère le token dans la db
function getTokenDB()
{
    $query = "SELECT token FROM Utilisateur WHERE token = :token";
    $stmt = getPDO()->prepare($query);
    $token = getToken();
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->execute();
    
    while ($row = $stmt->fetch())
    {
       $tokenRow = $row['token'];
       return $tokenRow;
    }
}

// Delete le token dans la db (déconnection)
function deleteToken()
{
    $query = "UPDATE Utilisateur SET token = null WHERE token = :token";
    $stmt = getPDO()->prepare($query);
    $token = getToken();
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->execute();
}

// Récupère le token dans le "Authorization"
function getToken() {
    $auth = getHeader("Authorization");
    if ($auth == null) {
    return null;
    }
    $auth = explode(" ", $auth);
    if (strcmp("Token", $auth[0]) != 0) {
    return null;
    }
    return $auth[1];
}

function getHeader($key) {
    foreach (getallheaders() as $name => $value) {
    if (strcmp(strtolower($key), strtolower($name)) == 0) {
    return $value;
    }
    }
    return null;
}

// Récupère l'id du user selon l'annonce
function getIdUserAd($idAdd)
{
    $query = "SELECT Utilisateur_idUtilisateur FROM Annonce WHERE idAnnonce = :idAdd";
    $stmt = getPDO()->prepare($query);
    $stmt->bindParam(":idAdd", $idAdd, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch())
    {
       $idUser = $row['Utilisateur_idUtilisateur'];
       return $idUser;
    }
}

// Récupère le token selon l'id de l'utilisateur
function getTokenById($idUser)
{
    $query = "SELECT Token FROM Utilisateur WHERE idUtilisateur = :idUser";
    $stmt = getPDO()->prepare($query);
    $stmt->bindParam(":idUser", $idUser, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch())
    {
       $token = $row['Token'];
       return $token;
    }
}

// Verifie si l'annonce exist deja dans la db
function adExist($idAdd) :bool
{
    $query = "SELECT * from Annonce WHERE idAnnonce = :idAdd";
    $stmt = getPDO()->prepare($query);
    $stmt->bindParam(":idAdd", $idAdd, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;      
}




