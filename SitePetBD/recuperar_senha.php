<?php
session_start();
require_once 'Usuario.php'; // Ajuste o caminho se precisar

// Mostrar erros para debug (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';

    if ($email && $nova_senha) {
        $usuario = new Usuario();
        if ($usuario->redefinirSenha($email, $nova_senha)) {
            // Sucesso: redireciona para login
            header("Location: loginpage.php");
            exit;
        } else {
            $mensagem = "❌ Erro ao redefinir senha. Verifique o e-mail.";
        }
    } else {
        $mensagem = "⚠️ Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Recuperar Senha</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <nav class="navbar">
    <ul class="menu">
      <li><a href="index.php">Início</a></li>
      <li><a href="loginpage.php">Entrar</a></li>
      <li><a href="registro.php">Registrar</a></li>
    </ul>
  </nav>
</header>

<section class="section">
  <h2>Recuperar Senha</h2>
  <?php if ($mensagem): ?>
    <p style="color: red;"><?= htmlspecialchars($mensagem) ?></p>
  <?php endif; ?>
  <form method="POST" class="form-container">
    <input type="email" name="email" placeholder="Seu e-mail" required><br>
    <input type="password" name="nova_senha" placeholder="Nova senha" required><br>
    <button type="submit" class="btn-icon purple">Redefinir Senha</button>
  </form>
</section>

</body>
</html>
