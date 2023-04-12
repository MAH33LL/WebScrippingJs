const puppeteer = require('puppeteer');

const linkPartidas = 'https://api.globoesporte.globo.com/tabela/d1a37fa4-e948-43a6-ba53-ab24ab3a45b1/fase/fase-unica-campeonato-brasileiro-2023/rodada/1/jogos/';

//Busca pelo Id do jogo na API Globo-GE
const buscarJogoPorId = async (id) => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto(linkPartidas);

  const content = await page.evaluate(() => {
    return JSON.parse(document.querySelector('body').textContent);
  });

  await browser.close();

  const jogo = content.find(jogo => jogo.id === id);

  if (jogo) {
    console.log(jogo);
  } else {
    console.log(`Não foi encontrado nenhum jogo com o ID ${id}`);
  }
}
//mostra Resultado Id
buscarJogoPorId(302573);