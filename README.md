# WebScrippingJs
SOBRE:

Este projeto consiste em uma aplicação web que exibe jogos de futebol de uma determinada data. A aplicação é composta por dois arquivos: index.php e buscarData.mjs.

ARQUIVO index.php:

O arquivo index.php é a página principal da aplicação. Ele faz uma chamada a uma API em http://localhost:3000/jogos/2023-04-15 e retorna os jogos de futebol daquela data. A tabela de jogos é exibida na página e é possível filtrar os jogos por um time específico. Há também uma funcionalidade para salvar os jogos em um arquivo PHP.

ARQUIVO buscarData.mjs:

O arquivo buscarData.mjs é uma aplicação feita em JavaScript que busca os jogos de futebol em uma API externa e retorna os jogos de uma determinada data. Além disso, a aplicação também envia os dados dos jogos para outro arquivo PHP para salvar.

PRÉ-REQUISITOS:

Node.js instalado
npm instalado
Visual Studio Code ou algum outro editor de código

INSTALAÇÃO:

Clone ou faça o download deste projeto.
Instale as dependências necessárias executando o seguinte comando no terminal:
Copy code
npm install puppeteer express cors

Execute o arquivo buscarData.mjs no terminal com o seguinte comando:

Copy code
node buscarData.mjs
Abra o arquivo index.php em um servidor web, como o XAMPP ou o Apache, e acesse-o em seu navegador.


Ao acessar o arquivo index.php em seu navegador, você verá uma tabela de jogos de futebol da data 2023-04-15. É possível filtrar os jogos por um time específico digitando o nome do time na barra de pesquisa. Além disso, é possível mudar a data dos jogos a ser exibida na tabela digitando uma nova data na barra de pesquisa e clicando no botão "Buscar Jogo". Por fim, é possível salvar os jogos atualmente exibidos na tabela clicando no botão "Salvar Jogos".
