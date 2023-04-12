<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$rodada = isset($_GET['rodada']) ? (int)$_GET['rodada'] : 1;
$data = isset($_GET['data']) ? $_GET['data'] : '2023-04-15';

$apiUrl = "https://api.globoesporte.globo.com/tabela/d1a37fa4-e948-43a6-ba53-ab24ab3a45b1/fase/fase-unica-campeonato-brasileiro-2023/rodada/{$rodada}/jogos/";

$response = @file_get_contents($apiUrl);

if ($response === false) {
    http_response_code(500);
    echo json_encode(["error" => "Não foi possível recuperar os dados da API."]);
} else {
    echo $response;
}
?>
