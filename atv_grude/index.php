<?php

// 1. Conexão com o MySQL

$servername = "localhost";
$username = "root";
$password = "Senai@118";
$dbname = "jogadores";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// 2. Criar tabela 

$sqlCreate = "CREATE TABLE IF NOT EXISTS Jogadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    nacionalidade VARCHAR(100) NOT NULL,
    idade INT NOT NULL,
    numero INT NOT NULL
)";
$conn->query($sqlCreate);

$mensagem = '';


// 3. Inserir dados

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'inserir') {
    $nome = trim($_POST["nome"]);
    $nacionalidade = trim($_POST["nacionalidade"]);
    $idade = intval($_POST["idade"]);
    $numero = intval($_POST["numero"]);

    if ($nome == "" || $nacionalidade == "" || $idade <= 0 || $numero <= 0) {
        $mensagem = "<p style='color:red;'>Preencha os campos corretamente.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO Jogadores (nome, nacionalidade, idade, numero) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nome, $nacionalidade, $idade, $numero);

        if ($stmt->execute()) {
            $mensagem = "<p style='color:green;'>Jogador inserido com sucesso!</p>";
        } else {
            $mensagem = "<p style='color:red;'>Erro ao inserir: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
}


// 4. Excluir dados

if (isset($_GET['delete_id'])) {
    $idDelete = intval($_GET['delete_id']);
    $conn->query("DELETE FROM Jogadores WHERE id = $idDelete");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}


// 5. Editar dados

if (isset($_GET['edit_id'])) {
    $idEdit = intval($_GET['edit_id']);
    $resEdit = $conn->query("SELECT * FROM Jogadores WHERE id = $idEdit");
    $editJogador = $resEdit->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'editar') {
    $id = intval($_POST['id']);
    $nome = trim($_POST["nome"]);
    $nacionalidade = trim($_POST["nacionalidade"]);
    $idade = intval($_POST["idade"]);
    $numero = intval($_POST["numero"]);

    if ($nome == "" || $nacionalidade == "" || $idade <= 0 || $numero <= 0) {
        $mensagem = "<p style='color:red;'>Preencha os campos corretamente.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE Jogadores SET nome=?, nacionalidade=?, idade=?, numero=? WHERE id=?");
        $stmt->bind_param("ssiii", $nome, $nacionalidade, $idade, $numero, $id);

        if ($stmt->execute()) {
            $mensagem = "<p style='color:green;'>Jogador atualizado com sucesso!</p>";
        } else {
            $mensagem = "<p style='color:red;'>Erro ao atualizar: " . $conn->error . "</p>";
        }
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}


// 6. Selecionar todos os dados

$sqlAll = "SELECT * FROM Jogadores ORDER BY id ASC";
$result = $conn->query($sqlAll);


// 7. Contagem total

$sqlCount = "SELECT COUNT(*) AS total FROM Jogadores";
$resCount = $conn->query($sqlCount);
$linhaCount = $resCount->fetch_assoc();
$totalJogadores = $linhaCount['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BID COPA DO MUNDO</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="number"] { margin-bottom: 10px; padding: 5px; }
        input[type="submit"] { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>BID COPA DO MUNDO</h2>

    <!-- Mensagem -->
    <?= $mensagem ?>

    <!-- Formulário para adicionar/editar jogador -->
    <h3><?= isset($editJogador) ? "Editar jogador" : "Adicionar novo jogador" ?></h3>
    <form method="POST">
        <input type="hidden" name="acao" value="<?= isset($editJogador) ? 'editar' : 'inserir' ?>">
        <?php if(isset($editJogador)): ?>
            <input type="hidden" name="id" value="<?= $editJogador['id'] ?>">
        <?php endif; ?>
        Nome: <input type="text" name="nome" value="<?= $editJogador['nome'] ?? '' ?>" required><br>
        Nacionalidade: <input type="text" name="nacionalidade" value="<?= $editJogador['nacionalidade'] ?? '' ?>" required><br>
        Idade: <input type="number" name="idade" value="<?= $editJogador['idade'] ?? '' ?>" required><br>
        Número: <input type="number" name="numero" value="<?= $editJogador['numero'] ?? '' ?>" required><br>
        <input type="submit" value="<?= isset($editJogador) ? 'Atualizar' : 'Enviar' ?>">
        <?php if(isset($editJogador)): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>">Cancelar</a>
        <?php endif; ?>
    </form>

    <hr>

    <!-- Lista de jogadores -->
    <h3>Jogadores cadastrados (Total: <?= $totalJogadores ?>)</h3>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Nacionalidade</th>
                <th>Idade</th>
                <th>Número</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["nome"]) ?></td>
                    <td><?= htmlspecialchars($row["nacionalidade"]) ?></td>
                    <td><?= $row["idade"] ?></td>
                    <td><?= $row["numero"] ?></td>
                    <td>
                        <a href="?edit_id=<?= $row["id"] ?>">Editar</a> |
                        <a href="?delete_id=<?= $row["id"] ?>" onclick="return confirm('Deseja realmente excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nenhum jogador cadastrado.</p>
    <?php endif; ?>
</body>
</html>