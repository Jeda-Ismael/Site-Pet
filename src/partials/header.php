<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title ?? 'Encontre Seu Pet') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap">
  <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/../../public/style.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js" defer></script>
</head>
<body>

<header>
  <nav class="navbar">
    <a href="index.php" class="brand">
      <span class="brand-icon" id="navCat" aria-hidden="true"></span>
      Encontre Seu Pet
    </a>
    <button type="button" class="nav-toggle" id="navToggle" aria-label="Abrir menu" aria-expanded="false">
      <i class="fas fa-bars"></i>
    </button>
    <ul class="menu" id="navMenu">
      <li><a href="index.php">Início</a></li>
      <li><a href="<?= is_logged_in() ? 'cadastro.php' : 'loginpage.php'; ?>">Cadastrar Caso</a></li>
      <li><a href="casos.php">Casos Ativos</a></li>
      <li><a href="<?= is_logged_in() ? 'cadastro_adocao.php' : 'loginpage.php'; ?>">Colocar para Adoção</a></li>
      <li><a href="adocao.php">Pets para Adoção</a></li>
      <li><a href="sobre.php">Sobre</a></li>

      <li class="nav-divider" aria-hidden="true"></li>

      <?php if (is_logged_in()): ?>
        <li class="user-chip">
          <span class="user-avatar"><?= e(mb_strtoupper(mb_substr($_SESSION['usuario_nome'] ?? '?', 0, 1))) ?></span>
          <?= e($_SESSION['usuario_nome'] ?? '') ?>
        </li>
        <li><a href="painel.php" class="btn-icon outline nav-btn nav-icon-only" title="Painel"><i class="fas fa-gauge"></i><span class="sr-only">Painel</span></a></li>
        <li><a href="logout.php" class="btn-icon outline nav-btn nav-icon-only" title="Sair"><i class="fas fa-right-from-bracket"></i><span class="sr-only">Sair</span></a></li>
      <?php else: ?>
        <li><a href="loginpage.php" class="btn-icon outline nav-btn"><i class="fas fa-lock"></i> Entrar</a></li>
        <li><a href="registro.php" class="btn-icon purple nav-btn"><i class="fas fa-file-alt"></i> Registrar</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
