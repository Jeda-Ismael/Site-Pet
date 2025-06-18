<?php
session_start();
$uid = $_SESSION['usuario_id'] ?? null;
$uname = $_SESSION['usuario_nome'] ?? null;

$conn = new mysqli('localhost', 'root', '', 'pet');
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

$res = $conn->query("SELECT * FROM adocoes ORDER BY data_cadastro DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Pets para Adoção</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
  <nav class="navbar">
    <ul class="menu">
      <li><a href="index.php">Início</a></li>
      <li><a href="cadastro.php">Cadastrar Caso</a></li>
      <li><a href="casos.php">Casos Ativos</a></li>
      <li><a href="cadastro_adocao.php">Colocar para Adoção</a></li>
      <li><a href="adocao.php">Pets para Adoção</a></li>

      <?php if ($uname): ?>
        <li><strong>👋 Olá, <?= htmlspecialchars($uname); ?></strong></li>
        <li><a href="logout.php">Sair</a></li>
      <?php else: ?>
        <li><a href="loginpage.php"><i class="fas fa-lock"></i> Entrar</a></li>
        <li><a href="registro.php"><i class="fas fa-file-alt"></i> Registrar</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<section class="section">
  <h2>PETS PARA ADOÇÃO</h2>
  <div class="casos">
    <?php while ($r = $res->fetch_assoc()): ?>
      <div class="caso">
        <img src="<?= htmlspecialchars($r['imagem']); ?>" alt="Imagem do pet">
        <h3><?= htmlspecialchars($r['nome_pet']); ?></h3>
        <p><strong>Espécie:</strong> <?= htmlspecialchars($r['especie']); ?></p>
        <p><strong>Idade:</strong> <?= htmlspecialchars($r['idade']); ?></p>
        <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($r['descricao'])); ?></p>

        <?php if ($uid && $r['usuario_id'] == $uid): ?>
          <div class="acoes">
            <a href="editar_adocao.php?id=<?= $r['id']; ?>" class="btn-icon purple">✏️ Editar</a>
            <a href="excluir_adocao.php?id=<?= $r['id']; ?>" onclick="return confirm('Confirmar exclusão?');" class="btn-icon outline">🗑️ Excluir</a>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<footer>
  <p>© 2025 Encontre Seu Pet | Email: contato@encontreseupet.com | Tel: +5597984107960</p>
</footer>

</body>
</html>
