<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para o login se não estiver logado
    header("Location: login.php");
    exit;
}

// Dados do usuário armazenados na sessão
$nome = $_SESSION['usuario_nome'];
$email = $_SESSION['usuario_email'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel do Usuário</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <nav>
      <ul class="menu">
        <li><a href="index.php">Início</a></li>
        <li><a href="logout.php">Sair</a></li>
      </ul>
    </nav>
  </header>

  <section class="section">
    <h2>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h2>
    <p>Seu e-mail cadastrado: <?php echo htmlspecialchars($email); ?></p>
  </section>

  <footer>
    <p>© 2025 Encontre Seu Pet</p>
  </footer>
</body>
</html>
