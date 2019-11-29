<?php
header("Access-Control-Allow-Origin: *");
echo "salut";
$bdd_id = $_POST['id'];
$bdd_nom = $_POST['nom'];
$bdd_image = $_POST['image'];
$bdd_description = $_POST['description'];
$connection = new mysqli("localhost", "root", "", "manager_urbanisme");
$request = $connection->prepare("UPDATE `admin` SET nom='$bdd_nom' WHERE id =$bdd_id");
$request->execute();
$request->close();
$request = $connection->prepare("UPDATE `admin` SET image='$bdd_image' WHERE id =$bdd_id");
$request->execute();
$request->close();
$request = $connection->prepare("UPDATE `admin`SET description='$bdd_description' WHERE id =$bdd_id");
$request->execute();
$request->close();
$connection->close();
