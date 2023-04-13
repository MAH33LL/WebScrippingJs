<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/icon.webp" type="image/png">
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
<input type="text" id="busca-input" placeholder="Buscar equipes">
  <input type="number" id="busca-rodada" placeholder="Rodada" min="1" max="38" step="1" value="1">
  <button type="button" onclick="atualizarJogos()">Buscar Jogo</button>
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
  const buscaInput = document.getElementById("busca-input");
  const buscaRodadaInput = document.getElementById("busca-rodada");

  function enviarJogos() {
    filtrarJogos().then(jogosFiltrados => {
      salvarJogos(jogosFiltrados);
    });
  }

  let apiUrl = `https://api.globoesporte.globo.com/tabela/d1a37fa4-e948-43a6-ba53-ab24ab3a45b1/fase/fase-unica-campeonato-brasileiro-2023/rodada/1/jogos/`;
  const jogosTabela = document.getElementById('jogos-tabela').getElementsByTagName('tbody')[0];
  const nomeTimeInput = document.getElementById('busca-time');

  function atualizarJogos() {
    const buscaRodadaInput = document.getElementById('busca-rodada');
    apiUrl = `https://api.globoesporte.globo.com/tabela/d1a37fa4-e948-43a6-ba53-ab24ab3a45b1/fase/fase-unica-campeonato-brasileiro-2023/rodada/${buscaRodadaInput.value}/jogos/`;
    filtrarJogos();
  }

  function filtrarJogos() {
    return fetch(`proxy.php?rodada=${buscaRodadaInput.value}`)
      .then((response) => response.json())
      .then((jogos) => {
        const jogosFiltrados = jogos.filter(
          (jogo) =>
            jogo.equipes.mandante.nome_popular
              .toLowerCase()
              .includes(buscaInput.value.toLowerCase()) ||
            jogo.equipes.visitante.nome_popular
              .toLowerCase()
              .includes(buscaInput.value.toLowerCase())
        );
        exibirJogos(jogosFiltrados);
        return jogosFiltrados;
      })
      .catch((erro) => console.error("Erro ao buscar jogos:", erro));
  }

  function salvarJogos(jogos) {
    fetch('/salvar_jogos.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(jogos)
    })
    .then(response => response.text())
    .then(data => {
      console.log(data);
    })
    .catch(error => {
      console.error('Erro ao salvar jogos:', error);
    });
  }

  // Atualiza e salva jogos a cada 1 minuto
  setInterval(() => {
    atualizarJogos();
    filtrarJogos().then(jogosFiltrados => {
      salvarJogos(jogosFiltrados);
    });
  }, 1 * 60 * 1000);

  function exibirJogos(jogos) {
    jogosTabela.innerHTML = '';
    jogos.forEach((jogo) => {
      const linha = document.createElement('tr');
      linha.innerHTML = `
        <td>${jogo.data_realizacao}</td>
        <td>${jogo.hora_realizacao}</td>
        <td>${jogo.equipes.mandante.nome_popular}</td>
        <td>${jogo.placar_oficial_mandante} X ${jogo.placar_oficial_visitante}</td>
        <td>${jogo.equipes.visitante.nome_popular}</td>
      `;
      jogosTabela.appendChild(linha);
    });
  }

  atualizarJogos();
</script>
</body>
</html>