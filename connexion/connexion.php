<?php
// TODO : POur corriger les erreurs de nom de domaine placer vos fichier dans le dossier WWW ou HTDOCS

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
$request = $connection->prepare("SELECT email, motDePasse FROM utilisateur WHERE email = ?");
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
    echo "Bravo vous êtes connecté !";
} else {
    echo "Votre mot de passe est incorrect !";
}


?>