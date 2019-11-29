<?php
header("Access-Control-Allow-Origin: *");
echo "salut";
$bdd_id = $_POST['id'];
$connection = new mysqli("localhost", "root", "", "manager_urbanisme");
$request = $connection->prepare("DELETE FROM `admin` WHERE id ='$bdd_id'");
$request->execute();
$request->close();
$connection->close();
