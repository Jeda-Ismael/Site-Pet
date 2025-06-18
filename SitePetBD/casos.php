<?php
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? null;

$conn = new mysqli('localhost', 'root', '', 'pet');
if ($conn->connect_error) {
  die("Erro: " . $conn->connect_error);
}

$sql = "SELECT * FROM casos ORDER BY data_cadastro DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Casos Ativos</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <nav class="navbar">
    <ul class="menu">
      <li><a href="index.php">Início</a></li>
      <li><a href="<?= isset($_SESSION['usuario_id']) ? 'cadastro.php' : 'loginpage.php'; ?>">Cadastrar Caso</a></li>
      <li><a href="casos.php">Casos Ativos</a></li>
      <li><a href="<?= isset($_SESSION['usuario_id']) ? 'cadastro_adocao.php' : 'loginpage.php'; ?>">Colocar para Adoção</a></li>
      <li><a href="adocao.php">Pets para Adoção</a></li>

      <?php if (isset($_SESSION['usuario_nome'])): ?>
        <li><strong>👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?></strong></li>
        <li><a href="logout.php">Sair</a></li>
      <?php else: ?>
        <li><a href="loginpage.php"><i class="fas fa-lock"></i> Entrar</a></li>
        <li><a href="registro.php"><i class="fas fa-file-alt"></i> Registrar</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<section class="section">
  <h2>Casos de pets desaparecidos</h2>
  <div class="casos">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="caso">
        <img src="<?= htmlspecialchars($row['imagem']); ?>" alt="Imagem do pet">
        <h3><?= htmlspecialchars($row['nome_pet']); ?></h3>
        <p><strong>Espécie:</strong> <?= htmlspecialchars($row['especie']); ?></p>
        <p><strong>Desapareceu em:</strong> <?= htmlspecialchars($row['data_cadastro']); ?></p>
        <p><strong>Local:</strong> <?= htmlspecialchars($row['local']); ?></p>
        <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($row['descricao'])); ?></p>
        <p><strong>Contato:</strong> <?= htmlspecialchars($row['contato']); ?></p>

        <?php if ($usuario_id && $row['usuario_id'] == $usuario_id): ?>
          <div class="acoes">
            <a href="editar_caso.php?id=<?= $row['id']; ?>" class="btn-icon purple">✏️ Editar</a>
            <a href="excluir_caso.php?id=<?= $row['id']; ?>" onclick="return confirm('Deseja mesmo excluir este caso?');" class="btn-icon outline">🗑️ Excluir</a>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<footer>
  <p>© 2025 Encontre Seu Pet | Email: contato@encontreseupet.com | Tel: +5597984107960</p>
</footer>

</body>
</html>

