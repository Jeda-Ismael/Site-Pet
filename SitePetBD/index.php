<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Encontre Seu Pet</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

<section class="hero">
  <div class="hero-left">
    <h1>Encontre Seu Pet</h1>
    <p>Junte-se à missão de encontrar pets perdidos</p>

    <div class="hero-buttons">
      <a href="<?= isset($_SESSION['usuario_id']) ? 'cadastro_adocao.php' : 'loginpage.php'; ?>" class="btn-icon purple">
        <i class="fas fa-heart"></i> Colocar para Adoção
      </a>
      <a href="adocao.php" class="btn-icon outline">
        <i class="fas fa-paw"></i> Pets para Adoção
      </a>
    </div>

    <div class="hero-buttons">
      <a href="<?= isset($_SESSION['usuario_id']) ? 'cadastro.php' : 'loginpage.php'; ?>" class="btn-icon purple">
        <i class="fas fa-search"></i> Perdi meu Pet
      </a>
      <a href="casos.php" class="btn-icon outline">
        <i class="fas fa-paw"></i> Achei um Pet
      </a>
    </div>

    <?php if (!isset($_SESSION['usuario_id'])): ?>
    <div class="hero-buttons">
      <a href="loginpage.php" class="btn-icon outline"><i class="fas fa-lock"></i> Entrar</a>
      <a href="registro.php" class="btn-icon purple"><i class="fas fa-file-alt"></i> Registrar</a>
    </div>
    <?php endif; ?>
  </div>

  <div class="hero-right"> 
    <img src="img1.png" alt="Gato decorativo">
  </div> 
</section>

<footer>
  <p>© 2025 Encontre Seu Pet | Email: contato@encontreseupet.com | Tel: +5597984107960</p>
</footer>
</body>
</html>
