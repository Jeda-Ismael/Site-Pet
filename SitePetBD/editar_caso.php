<?php
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    die("Acesso negado.");
}

$conn = new mysqli('localhost', 'root', '', 'pet');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do caso não fornecido.");
}

$stmt = $conn->prepare("SELECT * FROM casos WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$caso = $result->fetch_assoc();

if (!$caso) {
    die("Caso não encontrado ou acesso não autorizado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Caso</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <nav>
    <ul class="menu">
      <li><a href="index.php">Início</a></li>
      <li><a href="cadastro.php">Cadastrar Caso</a></li>
      <li><a href="casos.php">Casos Ativos</a></li>
      <li><a href="sobre.html">Sobre</a></li>
    </ul>
  </nav>
</header>

<section class="section">
  <h2>Editar caso</h2>

  <form action="atualizar_caso.php" method="POST" enctype="multipart/form-data" class="form-container">
    <input type="hidden" name="id" value="<?= $caso['id'] ?>">

    <input type="text" name="especie" value="<?= htmlspecialchars($caso['especie']) ?>" placeholder="Espécie do Pet" required>
    <input type="text" name="nome_pet" value="<?= htmlspecialchars($caso['nome_pet']) ?>" placeholder="Nome do pet" required>
    <input type="text" name="nome_tutor" value="<?= htmlspecialchars($caso['nome_tutor']) ?>" placeholder="Nome do tutor" required>
    <input type="tel" name="contato" value="<?= htmlspecialchars($caso['contato']) ?>" placeholder="Número para contato" required>
    <input type="text" name="local" value="<?= htmlspecialchars($caso['local']) ?>" placeholder="Local de desaparecimento" required>
    <input type="date" name="data" value="<?= date('Y-m-d', strtotime($caso['data_cadastro'])) ?>" required>
    <textarea name="descricao" placeholder="Descrição do pet" required><?= htmlspecialchars($caso['descricao']) ?></textarea>

    <p>Imagem atual:</p>
    <?php if (!empty($caso['imagem']) && file_exists($caso['imagem'])): ?>
      <img src="<?= $caso['imagem'] ?>" alt="Imagem atual" width="120">
    <?php else: ?>
      <p>Nenhuma imagem disponível.</p>
    <?php endif; ?>

    <input type="file" name="imagem"> <!-- imagem nova opcional -->

    <button type="submit" class="btn-icon purple">💾 Atualizar</button>
  </form>
</section>

<footer>
  <p>© 2025 Encontre Seu Pet | Email: contato@encontreseupet.com | Tel: +5597984107960</p>
</footer>

</body>
</html>
