<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <header>
    <nav class="navbar">
      <ul class="menu">
        <li><a href="index.php">Início</a></li>
        <li><a href="cadastro.php">Cadastrar Caso</a></li>
        <li><a href="casos.php">Casos Ativos</a></li>
        <li><a href="sobre.php">Sobre</a></li>

        <?php if (isset($_SESSION['usuario_nome'])): ?>
          <li><strong>👋 Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong></li>
          <li><a href="logout.php">Sair</a></li>
        <?php else: ?>
          <li><a href="login.php"><i class="fas fa-lock"></i> Entrar</a></li>
          <li><a href="registro.php"><i class="fas fa-file-alt"></i> Registrar</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <section class="section">
    <h2>Crie sua conta</h2>
    <div class="form-container">
      <form action="salvar_registro.php" method="POST">
        <input name="nome" type="text" placeholder="Nome completo" required>
        <input name="email" type="email" placeholder="E-mail" required>
        <input name="senha" type="password" placeholder="Senha" required>
        <input name="confirma_senha" type="password" placeholder="Confirme sua senha" required>
        <button type="submit" class="btn-icon purple">
          <i class="fas fa-file-alt"></i> Registrar
        </button>
      </form>
    </div>
  </section>

  <footer>
    <p>© 2025 Encontre Seu Pet | Email: contato@encontreseupet.com | Tel: +5597984107960</p>
  </footer>
</body>
</html>
