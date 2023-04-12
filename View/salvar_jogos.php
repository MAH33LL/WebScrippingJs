<?php
require_once 'conexao-reserva.php'; // Substitua pelo nome do arquivo que contém a conexão com o banco de dados

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data)) {
    foreach ($data as $jogo) {
        // Substitua os nomes das colunas pelos nomes corretos de suas colunas no banco de dados
        $sql = "INSERT INTO jogos (data_jogo, hora_jogo, mandante_id, mandante_sigla, mandante_nome, mandante_escudo, visitante_id, visitante_sigla, visitante_nome, visitante_escudo, placar_mandante, placar_visitante)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param(
            'ssisisssisii',
            $jogo['data'],
            $jogo['hora'],
            $jogo['mandante']['id'],
            $jogo['mandante']['sigla'],
            $jogo['mandante']['nome'],
            $jogo['mandante']['escudo'],
            $jogo['visitante']['id'],
            $jogo['visitante']['sigla'],
            $jogo['visitante']['nome'],
            $jogo['visitante']['escudo'],
            $jogo['placar_mandante'],
            $jogo['placar_visitante']
        );
        
        $stmt->execute();
    }
    echo "Jogos salvos com sucesso!";
} else {
    echo "Nenhum jogo encontrado para salvar.";
}
?>
