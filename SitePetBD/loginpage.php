<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="style.css" />
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
  <h2>Entrar</h2>
  <div class="form-container">
    <form action="login.php" method="POST">
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit" class="btn-icon purple">
        <i class="fas fa-sign-in-alt"></i> Entrar
      </button>
    </form>
    <!-- ✅ Link para recuperação de senha -->
    <p style="margin-top: 10px;">
      <a href="recuperar_senha.php">Esqueceu a senha?</a>
    </p>
  </div>
</section>

</body>
</html>
