<?php
require_once '../conexao.php';

// Alteração aqui: ler os dados POST
$inputJSON = file_get_contents('php://input');
$jogos = json_decode($inputJSON, true);

if (!empty($jogos) && is_array($jogos)) {
    foreach ($jogos as $jogo) {
        $mandante = $jogo['equipes']['mandante'] ?? [];
        $visitante = $jogo['equipes']['visitante'] ?? [];

        $mandante_nome = $mandante['nome_popular'] ?? '';
        $visitante_nome = $visitante['nome_popular'] ?? '';

        $sql = "INSERT INTO jogos (mandante_nome, visitante_nome)
                VALUES ('$mandante_nome', '$visitante_nome')";

        if ($conn->query($sql) === TRUE) {
            echo "Jogo registrado com sucesso<br>";
        } else {
            echo "Erro: " . $conn->error . "<br>";
        }
    }
}

$conn->close();
?>
