<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "apige";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("A Conexão com o Banco de dados Falhou: " . $conn->connect_error);
}
?>