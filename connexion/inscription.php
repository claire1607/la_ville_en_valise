<?php

// TODO : POur corriger les erreurs de nom de domaine placer vos fichier dans le dossier WWW ou HTDOCS

// On récupere les valeurs envoyé par la requete AJAX / formulaire
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Vérifier que toutes les informations ont bien été envoyé
if (!$email || !$password || !$confirmPassword) {
    echo "Le formulaire est mal rempli";
    exit(); // On termine le programme
}

// On verifie que le mot de passe est different du confirm password
if ($password != $confirmPassword) {
    echo "Le mot de passe ne correspond pas!";
    exit(); // On termine le programme
}

// On vérifie la syntaxe de l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Votre email est incorrect!";
    exit(); // On termine le programme
}

// On prepare la hash du mot de passe
$hash = password_hash($password, PASSWORD_DEFAULT);

// Il faut établir une connexion avec la base de donnée
$connection = new mysqli("localhost", "root", "", "manager_urbanisme");
// Il faut préparer la requete SQL
$request = $connection->prepare("INSERT INTO utilisateur (email, motDePasse) VALUES (?, ?)");
// On renseigne les valeurs dynamiques de la requete
$request->bind_param("ss", $email, $hash);
// On execute la requete
$request->execute();
// On ferme la connexion avec la base de donnée et la requette
$request->close();
$connection->close();

echo "Inscription réussi !"
// ps : il faudra vérifier que l'utilisateur n'est pas déja inscrit

?>