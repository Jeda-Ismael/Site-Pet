<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';

partial('header', ['title' => 'Encontre Seu Pet']);
?>

<section class="hero">
  <div class="hero-left">
    <span class="hero-eyebrow">🐾 Feito por quem ama bicho</span>
    <h1>Encontre <span class="highlight">Seu Pet</span></h1>
    <p>Junte-se à missão de encontrar pets perdidos e ajudar outros a encontrar um novo lar.</p>

    <div class="hero-buttons">
      <a href="<?= is_logged_in() ? 'cadastro_adocao.php' : 'loginpage.php'; ?>" class="btn-icon purple">
        <i class="fas fa-heart"></i> Colocar para Adoção
      </a>
      <a href="adocao.php" class="btn-icon outline">
        <i class="fas fa-paw"></i> Pets para Adoção
      </a>
    </div>

    <div class="hero-buttons">
      <a href="<?= is_logged_in() ? 'cadastro.php' : 'loginpage.php'; ?>" class="btn-icon purple">
        <i class="fas fa-search"></i> Perdi meu Pet
      </a>
      <a href="casos.php" class="btn-icon outline">
        <i class="fas fa-paw"></i> Achei um Pet
      </a>
    </div>

    <?php if (!is_logged_in()): ?>
    <div class="hero-buttons">
      <a href="loginpage.php" class="btn-icon outline"><i class="fas fa-lock"></i> Entrar</a>
      <a href="registro.php" class="btn-icon purple"><i class="fas fa-file-alt"></i> Registrar</a>
    </div>
    <?php endif; ?>
  </div>

  <div class="hero-right">
    <div id="heroCat" class="hero-lottie" aria-label="Gato animado gigante"></div>
  </div>
</section>

<?php partial('footer'); ?>
