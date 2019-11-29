<?php
header("Access-Control-Allow-Origin: *");
$connection = new mysqli("localhost", "root", "", "manager_urbanisme");
$request = $connection->prepare("SELECT id, nom, image, description FROM admin");
$request->execute();
$bdd_id = null;
$bdd_nom = null;
$bdd_image = null;
$bdd_description = null;
$request->bind_result($bdd_id, $bdd_nom, $bdd_image, $bdd_description);
$pizzas = [];
while ($request->fetch()) {
    $pizzas[] = [
        "id" => $bdd_id,
        "nom" => $bdd_nom,
        "image" => $bdd_image,
        "description" => $bdd_description
    ];
}
echo json_encode($pizzas);
$request->close();
$connection->close();
