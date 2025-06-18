<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: loginpage.php");
  exit;
}

$conn = new mysqli('localhost', 'root', '', 'pet');
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
$usuario_id = $_SESSION['usuario_id'];

if (!$id) {
  die("ID inválido.");
}

// Verifica se é do usuário logado
$stmt = $conn->prepare("SELECT * FROM adocoes WHERE id=? AND usuario_id=?");
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$adocao = $result->fetch_assoc();

if (!$adocao) {
  die("Pet não encontrado ou sem permissão.");
}

// Se enviou o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $especie = $_POST['especie'];
  $nome_pet = $_POST['nome_pet'];
  $idade = $_POST['idade'];
  $descricao = $_POST['descricao'];

  $sql = "UPDATE adocoes SET especie=?, nome_pet=?, idade=?, descricao=? WHERE id=? AND usuario_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssiii", $especie, $nome_pet, $idade, $descricao, $id, $usuario_id);
  if ($stmt->execute()) {
    header("Location: adocao.php");
    exit;
  } else {
    echo "Erro ao atualizar.";
  }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Pet para Adoção</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <nav class="navbar">
    <ul class="menu">
      <li><a href="index.php">Início</a></li>
      <li><a href="cadastro.php">Cadastrar Caso</a></li>
      <li><a href="casos.php">Casos Ativos</a></li>
      <li><a href="sobre.php">Sobre</a></li>
      <li><a href="cadastro_adocao.php">Colocar para Adoção</a></li>
      <li><a href="adocao.php">Pets para Adoção</a></li>
      <?php if (isset($_SESSION['usuario_nome'])): ?>
        <li><strong>👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?></strong></li>
        <li><a href="logout.php">Sair</a></li>
      <?php else: ?>
        <li><a href="loginpage.php">Entrar</a></li>
        <li><a href="registro.php">Registrar</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<section class="section">
  <h2>Editar Pet para Adoção</h2>
  <form method="POST">
    <input name="especie" value="<?= htmlspecialchars($adocao['especie']); ?>" required placeholder="Espécie">
    <input name="nome_pet" value="<?= htmlspecialchars($adocao['nome_pet']); ?>" required placeholder="Nome do pet">
    <input name="idade" value="<?= htmlspecialchars($adocao['idade']); ?>" required placeholder="Idade">
    <textarea name="descricao" required placeholder="Descrição"><?= htmlspecialchars($adocao['descricao']); ?></textarea>
    <button type="submit" class="btn-icon purple">Salvar Alterações</button>
  </form>
</section>

</body>
</html>
