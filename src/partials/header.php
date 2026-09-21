<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="color-scheme" content="light" />
  <title><?= e($title ?? 'Encontre Seu Pet') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap">
  <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/../../public/style.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js" defer></script>
</head>
<body>

<header<?= !empty($overlayNav) ? ' class="header-overlay"' : '' ?>>
  <nav class="navbar<?= !empty($overlayNav) ? ' navbar-overlay' : '' ?>">
    <a href="index.php" class="brand">
      <span class="brand-icon" id="navCat" aria-hidden="true"></span>
      Encontre Seu Pet
    </a>
    <button type="button" class="nav-toggle" id="navToggle" aria-label="Abrir menu" aria-expanded="false">
      <i class="fas fa-bars"></i>
    </button>
    <ul class="menu" id="navMenu">
      <li class="menu-pill">
        <a href="index.php">Início</a>
        <a href="casos.php">Casos</a>
        <a href="adocao.php">Adoção</a>
        <a href="sobre.php">Sobre</a>
      </li>

      <li class="nav-divider" aria-hidden="true"></li>

      <li class="nav-profile">
        <button type="button" class="profile-trigger" id="profileTrigger" aria-haspopup="true" aria-expanded="false" title="Conta">
          <?php if (is_logged_in()): ?>
            <span class="user-avatar"><?= e(mb_strtoupper(mb_substr($_SESSION['usuario_nome'] ?? '?', 0, 1))) ?></span>
          <?php else: ?>
            <i class="fas fa-user"></i>
          <?php endif; ?>
        </button>

        <div class="profile-dropdown" id="profileDropdown">
          <?php if (is_logged_in()): ?>
            <div class="profile-dropdown-header">
              <span class="user-avatar"><?= e(mb_strtoupper(mb_substr($_SESSION['usuario_nome'] ?? '?', 0, 1))) ?></span>
              <strong><?= e($_SESSION['usuario_nome'] ?? '') ?></strong>
            </div>
            <a href="painel.php"><i class="fas fa-gauge"></i> Painel</a>
            <a href="logout.php"><i class="fas fa-right-from-bracket"></i> Sair</a>
          <?php else: ?>
            <a href="loginpage.php" class="btn-icon outline"><i class="fas fa-lock"></i> Entrar</a>
            <a href="registro.php" class="btn-icon purple"><i class="fas fa-file-alt"></i> Registrar</a>
          <?php endif; ?>
        </div>
      </li>
    </ul>
  </nav>
</header>
