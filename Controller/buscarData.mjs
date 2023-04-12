import puppeteer from 'puppeteer';
import express from 'express';
import cors from 'cors';
import fetch from 'node-fetch';

const app = express();
const port = 3000;

app.use(cors());

const linkPartidas = 'https://api.globoesporte.globo.com/tabela/d1a37fa4-e948-43a6-ba53-ab24ab3a45b1/fase/fase-unica-campeonato-brasileiro-2023/rodada/1/jogos/';

const buscarJogosPorData = async (data) => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto(linkPartidas);

  const content = await page.evaluate(() => {
    return JSON.parse(document.querySelector('body').textContent);
  });

  await browser.close();

  return content.filter(jogo => jogo.data_realizacao.startsWith(data));
};

function extrairInformacoesJogo(jogo) {
  return {
    data: jogo.data_realizacao,
    hora: jogo.hora_realizacao,
    placar_mandante: jogo.placar_oficial_mandante,
    placar_visitante: jogo.placar_oficial_visitante,
    mandante: {
      nome: jogo.equipes.mandante.nome_popular,
      sigla: jogo.equipes.mandante.sigla,
      escudo: jogo.equipes.mandante.escudo,
    },
    visitante: {
      nome: jogo.equipes.visitante.nome_popular,
      sigla: jogo.equipes.visitante.sigla,
      escudo: jogo.equipes.visitante.escudo,
    }
  };
}

const nomeTime = process.argv[2];

async function enviarDadosParaSalvar(jogos) {
  try {
    const response = await fetch('http://localhost/ZONA-DE-TESTES/WebScrippingJs/Controller/saveGameData.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(jogos),
    });

    const responseText = await response.text();
    console.log(responseText);
  } catch (error) {
    console.error('Erro ao enviar dados para salvar:', error);
  }
}

async function main() {
  const jogos = await buscarJogosPorData('2023-04-15');
  console.log(jogos);

  enviarDadosParaSalvar(jogos);
}

if (import.meta.url === `file://${process.argv[1]}`) {
  main();
}

app.get('/jogos/:data', async (req, res) => {
  const data = req.params.data;
  const jogosNoDia = await buscarJogosPorData(data);

  if (jogosNoDia.length > 0) {
    const resultados = jogosNoDia.map(extrairInformacoesJogo);
    res.json(resultados);
  } else {
    res.status(404).json({ message: `Não foram encontrados jogos na data ${data}` });
  }
});

app.listen(port, () => {
  console.log(`Servidor rodando em http://localhost:${port}/jogos/2023-04-15`);
});
