<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canil";


$ligax = new mysqli($servername, $username, $password, $dbname);


if ($ligax->connect_error) {
    die("Falha na conexão com o banco de dados: " . $ligax->connect_error);
}


$ligax->set_charset("utf8");


?>
