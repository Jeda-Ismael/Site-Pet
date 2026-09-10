<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    if (login_is_blocked()) {
        $erro = 'Muitas tentativas de login. Tente novamente em ' . login_seconds_remaining() . ' segundos.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $stmt = Database::connection()->prepare('SELECT id, nome, email, senha FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            login_user((int) $user['id'], $user['nome'], $user['email']);
            header('Location: index.php');
            exit;
        }

        register_failed_login();
        $erro = 'E-mail ou senha incorretos.';
    }
}

partial('header', ['title' => 'Login']);
?>

<section class="section">
  <h2>Entrar</h2>
  <div class="form-container">
    <?php if ($erro): ?>
      <p class="erro"><?= e($erro) ?></p>
    <?php endif; ?>
    <form action="loginpage.php" method="POST">
      <?= csrf_field() ?>
      <input name="email" type="email" placeholder="E-mail" required>
      <input name="senha" type="password" placeholder="Senha" required>
      <button type="submit" class="btn-icon purple">
        <i class="fas fa-sign-in-alt"></i> Entrar
      </button>
    </form>
  </div>
</section>

<?php partial('footer'); ?>
