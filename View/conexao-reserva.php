<?php
// Substitua pelos dados de conexão do seu banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "apige";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
