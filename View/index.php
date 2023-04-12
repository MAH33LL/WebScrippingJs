<?php
$apiUrl = 'http://localhost:3000/jogos/2023-04-15';
$response = file_get_contents($apiUrl);
$jogos = json_decode($response, true);

if (empty($jogos)) {
  echo "Não foram encontrados jogos na data " . date("d/m/Y");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Jogos do Dia</title>
    <style>
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
<form>
  <input type="text" id="busca-data" placeholder="YYYY-MM-DD">
  <button type="button" onclick="atualizarJogos()">Buscar Jogo</button>
  <br><br>
  <input type="text" id="busca-time" placeholder="Procurar time...">
  <button type="button" onclick="filtrarJogos()">Procurar</button>
  <br><br>
  <button type="button" onclick="enviarJogos()">Salvar Jogos</button>
</form>

<p>
  <span style="font-weight: bold; font-size: 15px;" id="txt-horas">Horas:</span>
  <span id="clock" style="font-size: 20px;"></span>
</p>

<script>
  const clock = document.getElementById("clock");
  setInterval(() => {
    const data = new Date();
    const horas = data.toLocaleTimeString("pt-BR", {timeZone: "America/Sao_Paulo"});
    clock.innerText = horas;
  }, 1000);
</script>

<p><span style="color: white;">JOGOS DO DIA </span><span style="color: red;"></span></p>


    <table id="jogos-tabela">
        <thead>
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Mandante</th>
                <th>Placar</th>
                <th>Visitante</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script>
  // Adicione a função enviarJogos() aqui
  function enviarJogos() {
    fetch(apiUrl)
      .then(response => response.json())
      .then(jogos => {
        fetch('salvar_jogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(jogos),
        })
          .then(response => response.text())
          .then(result => alert(result))
          .catch(error => console.error('Erro ao salvar jogos:', error));
      })
      .catch(error => console.error('Erro ao buscar jogos:', error));
  }
  
  // Restante do seu código JavaScript
</script>

    <script>
  let apiUrl = 'http://localhost:3000/jogos/2023-04-15';
  const buscaDataInput = document.getElementById('busca-data');
  const jogosTabela = document.getElementById('jogos-tabela').getElementsByTagName('tbody')[0];
  const nomeTimeInput = document.getElementById('busca-time');

  function atualizarJogos() {
    apiUrl = `http://localhost:3000/jogos/${buscaDataInput.value}`;
    filtrarJogos();
  }

  function filtrarJogos() {
    fetch(apiUrl)
      .then(response => response.json())
      .then(jogos => {
        const nomeTime = nomeTimeInput.value.toLowerCase();
        const jogosFiltrados = jogos.filter(jogo => jogo.mandante.nome.toLowerCase().includes(nomeTime) || jogo.visitante.nome.toLowerCase().includes(nomeTime));
        jogosTabela.innerHTML = '';
        jogosFiltrados.forEach(jogo => {
          const newRow = jogosTabela.insertRow();
          newRow.innerHTML = `
            <td>${new Date(jogo.data).toLocaleDateString()}</td>
            <td>${jogo.hora}</td>
            <td>
              <img src="${jogo.mandante.escudo}" alt="${jogo.mandante.sigla}" width="50">
              ${jogo.mandante.nome}
            </td>
            <td>${jogo.placar_mandante !== null && jogo.placar_visitante !== null ? jogo.placar_mandante + ' x ' + jogo.placar_visitante : '-'}</td>
            <td>
              <img src="${jogo.visitante.escudo}" alt="${jogo.visitante.sigla}" width="50">
              ${jogo.visitante.nome}
            </td>`;
        });
      });
  }

  filtrarJogos();
  setInterval(filtrarJogos, 10000);
</script>
<script src="../Controller/buscarData.mjs"></script>
</body>
</html>

