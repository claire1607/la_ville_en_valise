<?php
header("Access-Control-Allow-Origin: *");
// On récupere les valeurs envoyé par la requete AJAX / formulaire
$email = $_POST['email'];
$password = $_POST['password'];
// Vérifier que toutes les informations ont bien été envoyé
if (!$email || !$password) {
    echo "Le formulaire est mal rempli";
    exit(); // On termine le programme
}
// On vérifie la syntaxe de l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Votre email est incorrect!";
    exit(); // On termine le programme
}
// Il faut établir une connexion avec la base de donnée
$connection = new mysqli("localhost", "root", "", "manager_urbanisme");
// Il faut préparer la requete SQL
$request = $connection->prepare("SELECT email, password FROM client WHERE email = ?");
// On renseigne les valeurs dynamiques de la requete
$request->bind_param("s", $email);
// On execute la requete
$request->execute();
// On récupere l'email et mot de passe retourné par la base de donnée
$bdd_email = null; // Initialisé car plus pratique pour faire des if aprés
$bdd_password = null;
$request->bind_result($bdd_email, $bdd_password);
// On execute la récup des valeurs
$request->fetch();
// On ferme la connexion avec la base de donnée et la requette
$request->close();
$connection->close();
// On vérifie que la mot de passe entré dans le formulaire et le mot de passe de la bdd est identique pour valider la connexion
if (password_verify($password, $bdd_password)) {
    $tab = [
        'valid' => true,
        'message' => "Bravo vous êtes connecté !",
        'redirect' => "../accueil/accueil.html"
    ];
    echo json_encode($tab);
} else {
    $tab = [
        'valid' => false,
        'message' => "Mot de passe ou Email incorrect !",
        'redirect' => null
    ];
    echo json_encode($tab);
}
