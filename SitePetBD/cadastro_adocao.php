<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastre Pet para Adoção</title>
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
  <h2>Cadastro para Adoção</h2>
  <form action="salvar_adocao.php" method="POST" enctype="multipart/form-data">
    <input name="especie" required placeholder="Espécie">
    <input name="nome_pet" required placeholder="Nome do pet">
    <input name="idade" required placeholder="Idade">
    <textarea name="descricao" required placeholder="Descrição"></textarea>
    <input type="file" name="imagem" accept="image/*" required>
    <button type="submit" class="btn-icon purple">
      <i class="fas fa-file-alt"></i> Cadastrar 
    </button>
  </form>
</section>

<footer>
  <p>© 2025 Encontre Seu Pet | Email: contato@encontreseupet.com | Tel: +5597984107960</p>
</footer>

</body>
</html>
