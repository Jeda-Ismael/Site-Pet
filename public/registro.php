<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirma = $_POST['confirma_senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } elseif ($senha !== $confirma) {
        $erro = 'As senhas não conferem.';
    } elseif (strlen($senha) < 8) {
        $erro = 'A senha precisa ter pelo menos 8 caracteres.';
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        try {
            $stmt = Database::connection()->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, $hash]);
            header('Location: loginpage.php');
            exit;
        } catch (PDOException $e) {
            $erro = ($e->getCode() === '23000')
                ? 'Esse e-mail já está cadastrado.'
                : 'Erro ao registrar. Tente novamente.';
        }
    }
}

partial('header', ['title' => 'Cadastro']);
?>

<section class="section">
  <h2>Crie sua conta</h2>
  <div class="form-container">
    <?php if ($erro): ?>
      <p class="erro"><?= e($erro) ?></p>
    <?php endif; ?>
    <form action="registro.php" method="POST">
      <?= csrf_field() ?>
      <input name="nome" type="text" placeholder="Nome completo" value="<?= e($_POST['nome'] ?? '') ?>" required>
      <input name="email" type="email" placeholder="E-mail" value="<?= e($_POST['email'] ?? '') ?>" required>
      <input name="senha" type="password" placeholder="Senha (mínimo 8 caracteres)" required minlength="8">
      <input name="confirma_senha" type="password" placeholder="Confirme sua senha" required minlength="8">
      <button type="submit" class="btn-icon purple">
        <i class="fas fa-file-alt"></i> Registrar
      </button>
    </form>
  </div>
</section>

<?php partial('footer'); ?>
