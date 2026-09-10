<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require_login();

$nome = $_SESSION['usuario_nome'];
$email = $_SESSION['usuario_email'];

partial('header', ['title' => 'Painel do Usuário']);
?>

<section class="section">
  <h2>Bem-vindo, <?= e($nome) ?>!</h2>
  <p>Seu e-mail cadastrado: <?= e($email) ?></p>
</section>

<?php partial('footer'); ?>
