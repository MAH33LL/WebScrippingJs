<?php
require_once 'conexao-reserva.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data)) {
    $sql = "INSERT INTO jogos (data_jogo, hora_jogo, mandante_id, mandante_sigla, mandante_nome, visitante_id, visitante_sigla, visitante_nome, placar_mandante, placar_visitante)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            data_jogo = VALUES(data_jogo),
            hora_jogo = VALUES(hora_jogo),
            mandante_sigla = VALUES(mandante_sigla),
            mandante_nome = VALUES(mandante_nome),
            visitante_sigla = VALUES(visitante_sigla),
            visitante_nome = VALUES(visitante_nome),
            placar_mandante = VALUES(placar_mandante),
            placar_visitante = VALUES(placar_visitante)";
    
    try {
        $stmt = $conn->prepare($sql);

        foreach ($data as $jogo) {
            $stmt->bind_param("ssisssissi",
    $jogo['data_realizacao'],
    $jogo['hora_realizacao'],
    $jogo['equipes']['mandante']['id'],
    $jogo['equipes']['mandante']['sigla'],
    $jogo['equipes']['mandante']['nome_popular'],
    $jogo['equipes']['visitante']['id'],
    $jogo['equipes']['visitante']['sigla'],
    $jogo['equipes']['visitante']['nome_popular'],
    $jogo['placar_oficial_mandante'],
    $jogo['placar_oficial_visitante']
);

            $stmt->execute();
        }
        echo "Jogos atualizados com sucesso!";
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao atualizar jogos: " . $e->getMessage();
    }
} else {
    echo "Nenhum jogo encontrado para atualizar.";
}
?>
