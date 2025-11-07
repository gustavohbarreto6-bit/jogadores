# jogadoresBID COPA DO MUNDO

Este projeto é um sistema simples em PHP com MySQL para gerenciar jogadores da Copa do Mundo. Ele permite adicionar, listar e excluir jogadores, armazenando informações em um banco de dados.

📂 Estrutura do Projeto /projeto-bid/ │ ├── index.php # Página principal com o CRUD ├── style.css # Estilo da tabela e formulário ├── README.md # Este arquivo └── banco.sql # (Opcional) Script para criar o banco de dados

🛠 Requisitos

PHP 7.x ou superior

MySQL ou MariaDB

Servidor web local (XAMPP, WAMP ou similar)

Navegador moderno

⚙ Configuração

Criar banco de dados:

Acesse o MySQL e crie o banco:

CREATE DATABASE jogadores;

Configurar conexão no index.php:

$servername = "localhost"; $username = "root"; $password = "Senai@118"; $dbname = "jogadores";

Altere username e password conforme sua configuração local.

Rodar o projeto:

Copie os arquivos para a pasta htdocs (ou equivalente do seu servidor local).

Abra o navegador e acesse http://localhost/projeto-bid/.

📝 Funcionalidades

Adicionar jogador

Preencha nome, nacionalidade, idade e número.

Validação básica: todos os campos obrigatórios e números positivos.

Listar jogadores

Todos os jogadores cadastrados são exibidos em uma tabela.

Informações: ID, Nome, Nacionalidade, Idade, Número.

Excluir jogador

Clique no link "Excluir" ao lado do jogador desejado.

Confirmação antes da exclusão.

Contagem total

Exibe o número total de jogadores cadastrados.

💡 Melhorias Futuras

Adicionar edição de jogador.

Melhorar validação dos campos (ex.: idade mínima, números únicos).

Estilizar a tabela e o formulário com CSS ou frameworks como Bootstrap.

Criar sistema de login para proteger o CRUD.

👨‍💻 Autor

Gustavo Henrique Ferreira Barreto Projeto de estudo em PHP e MySQL para gerenciamento de jogadores da Copa do Mundo.
