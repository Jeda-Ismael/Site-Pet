<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';

$totalCasos = (int) Database::connection()->query("SELECT COUNT(*) FROM casos WHERE status = 'ativo'")->fetchColumn();
$totalAdocoes = (int) Database::connection()->query("SELECT COUNT(*) FROM adocoes WHERE status = 'disponivel'")->fetchColumn();

partial('header', ['title' => 'Encontre Seu Pet', 'overlayNav' => true]);
?>

<section class="hero">
  <div class="hero-sprite" id="heroCatSprite" aria-hidden="true">
    <canvas id="heroCatCanvas"></canvas>
    <div class="hero-sprite-fade"></div>
  </div>
  <div class="hero-overlay" aria-hidden="true"></div>

  <div class="hero-left">
    <span class="hero-eyebrow">🐾 Feito por quem ama bicho</span>
    <h1>Encontre <span class="highlight">Seu Pet</span></h1>
    <p>Junte-se à missão de encontrar pets perdidos e ajudar outros a encontrar um novo lar.</p>

    <div class="hero-buttons">
      <a href="<?= is_logged_in() ? 'cadastro_adocao.php' : 'loginpage.php'; ?>" class="btn-icon purple">
        <i class="fas fa-heart"></i> Colocar para Adoção
      </a>
      <a href="<?= is_logged_in() ? 'cadastro.php' : 'loginpage.php'; ?>" class="btn-icon outline">
        <i class="fas fa-search"></i> Perdi meu Pet
      </a>
    </div>
  </div>

  <div class="hero-stats">
    <a href="casos.php" class="hero-stat-card">
      <strong><?= $totalCasos ?></strong>
      <span><i class="fas fa-paw"></i> Casos ativos</span>
    </a>
    <a href="adocao.php" class="hero-stat-card">
      <strong><?= $totalAdocoes ?></strong>
      <span><i class="fas fa-house"></i> Pra adoção</span>
    </a>
  </div>
</section>

<script src="cat-sprite.js?v=<?= filemtime(__DIR__ . '/cat-sprite.js') ?>" defer></script>

<?php partial('footer'); ?>
