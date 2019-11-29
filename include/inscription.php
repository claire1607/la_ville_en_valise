  
<?php
header("Access-Control-Allow-Origin: *");
// TODO : POur corriger les erreurs de nom de domaine placer vos fichier dans le dossier WWW ou HTDOCS
// On récupere les valeurs envoyé par la requete AJAX / formulaire
$name = $_POST['nom'];
$fname = $_POST['fname'];
$email = $_POST['email'];
$password = $_POST['password'];
// Vérifier que toutes les informations ont bien été envoyé
if (!$name || !$fname || !$email ||  !$password) {
    $tab = [
        'message' => "Inscription réussi !"
    ];
    echo json_encode($tab);
    exit(); // On termine le programme
}
// On vérifie la syntaxe de l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $tab = [
        'message' => "Inscription réussi !"
    ];
    echo json_encode($tab);
    exit(); // On termine le programme
}
// On prepare la hash du mot de passe
$hash = password_hash($password, PASSWORD_DEFAULT);
// Il faut établir une connexion avec la base de donnée
$connection = new mysqli("localhost", "root", "", "manager_urbanisme");
// Il faut préparer la requete SQL
$request = $connection->prepare("INSERT INTO utilisateur (nom,fname, password, email) VALUES (?, ?, ?, ?)");
// On renseigne les valeurs dynamiques de la requete
$request->bind_param("ssssss", $name, $fname,  $hash, $email);
// On execute la requete
$request->execute();
// On ferme la connexion avec la base de donnée et la requette
$request->close();
$connection->close();
$tab = [
    'message' => "Inscription réussi !",
    'redirect' => "../accueil/accueil.html"
];
echo json_encode($tab);
