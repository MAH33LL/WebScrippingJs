import puppeteer from 'puppeteer';
import express from 'express';
import cors from 'cors';
import fetch from 'node-fetch';

const app = express();
const port = 3000;

app.use(cors());

const linkPartidasBase = 'https://api.globoesporte.globo.com/tabela/d1a37fa4-e948-43a6-ba53-ab24ab3a45b1/fase/fase-unica-campeonato-brasileiro-2023/rodada/';

const buscarJogosPorRodada = async (rodada) => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto(`${linkPartidasBase}${rodada}/jogos/`);

  const content = await page.evaluate(() => {
    return JSON.parse(document.querySelector('body').textContent);
  });

  await browser.close();

  return content;
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
  const rodadas = [1, 2]; // Adicione as rodadas que deseja buscar
  
  const jogos = [];
  for (const rodada of rodadas) {
    const jogosRodada = await buscarJogosPorRodada(rodada);
    jogos.push(...jogosRodada);
  }

  console.log(jogos);
  enviarDadosParaSalvar(jogos);
}

if (import.meta.url === `file://${process.argv[1]}`) {
  main();
}

app.get('/jogos/:rodada', async (req, res) => {
  const rodada = parseInt(req.params.rodada);
  const jogosNoDia = await buscarJogosPorRodada(rodada);

  if (jogosNoDia.length > 0) {
    const resultados = jogosNoDia.map(extrairInformacoesJogo);
    res.json(resultados);
  } else {
    res.status(404).json({ message: `Não foram encontrados jogos na rodada ${rodada}` });
  }
});

app.listen(port, () => {
  console.log(`Servidor rodando em http://localhost:${port}/jogos/1`);
});
