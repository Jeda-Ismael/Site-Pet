<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';

$usuarioId = current_user_id();
$stmt = Database::connection()->prepare("SELECT * FROM casos WHERE status = 'ativo' ORDER BY data_cadastro DESC");
$stmt->execute();
$casos = $stmt->fetchAll();

$especies = array_values(array_unique(array_filter(array_map(fn($c) => $c['especie'], $casos))));
sort($especies);

partial('header', ['title' => 'Casos Ativos']);
?>

<section class="section">
  <div class="section-header">
    <h2>Casos de pets desaparecidos</h2>
    <a href="<?= is_logged_in() ? 'cadastro.php' : 'loginpage.php'; ?>" class="btn-icon purple">
      <i class="fas fa-plus"></i> Cadastrar caso
    </a>
  </div>

  <?php if (!empty($casos)): ?>
  <div class="filters-bar">
    <div class="filter-search">
      <i class="fas fa-search"></i>
      <input type="text" id="filtroBusca" placeholder="Buscar por nome do pet ou local...">
    </div>
    <select id="filtroEspecie">
      <option value="">Todas as espécies</option>
      <?php foreach ($especies as $esp): ?>
        <option value="<?= e($esp) ?>"><?= e($esp) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>

  <div class="casos" id="listaCasos">
    <?php if (empty($casos)): ?>
      <p class="empty-state"><i class="fas fa-paw"></i> Nenhum caso cadastrado ainda.</p>
    <?php endif; ?>
    <?php foreach ($casos as $row): ?>
      <div class="caso"
           data-nome="<?= e(mb_strtolower($row['nome_pet'])) ?>"
           data-local="<?= e(mb_strtolower($row['local'])) ?>"
           data-especie="<?= e($row['especie']) ?>">
        <div class="caso-img-wrap">
          <img src="<?= e($row['imagem']) ?>" alt="Imagem do pet">
          <span class="caso-badge"><?= e($row['especie']) ?></span>
        </div>
        <h3><?= e($row['nome_pet']) ?></h3>
        <div class="caso-tags">
          <?php if (!empty($row['porte'])): ?><span class="tag"><i class="fas fa-ruler"></i> <?= e($row['porte']) ?></span><?php endif; ?>
          <?php if (!empty($row['sexo'])): ?><span class="tag"><i class="fas fa-<?= $row['sexo'] === 'Macho' ? 'mars' : 'venus' ?>"></i> <?= e($row['sexo']) ?></span><?php endif; ?>
          <?php if (!empty($row['cor'])): ?><span class="tag"><i class="fas fa-palette"></i> <?= e($row['cor']) ?></span><?php endif; ?>
        </div>
        <?php if (!empty($row['raca'])): ?><p><strong>Raça:</strong> <?= e($row['raca']) ?></p><?php endif; ?>
        <p><strong>Desapareceu em:</strong> <?= e($row['data_cadastro']) ?></p>
        <p><strong>Local:</strong> <?= e($row['local']) ?></p>
        <p><strong>Descrição:</strong> <?= nl2br(e($row['descricao'])) ?></p>
        <p><strong>Contato:</strong> <?= e($row['contato']) ?></p>

        <?php if ($usuarioId && (int) $row['usuario_id'] === $usuarioId): ?>
          <div class="acoes">
            <a href="editar_caso.php?id=<?= (int) $row['id'] ?>" class="btn-icon purple">✏️ Editar</a>
            <form action="marcar_encontrado.php" method="POST" onsubmit="return confirm('Marcar este caso como encontrado? Ele sairá da lista de casos ativos.');" style="display:contents">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
              <button type="submit" class="btn-icon found">🎉 Encontrado</button>
            </form>
            <form action="excluir_caso.php" method="POST" onsubmit="return confirm('Deseja mesmo excluir este caso?');" style="display:contents">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
              <button type="submit" class="btn-icon outline">🗑️ Excluir</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <p class="empty-state" id="semResultados" hidden><i class="fas fa-magnifying-glass"></i> Nenhum caso encontrado com esse filtro.</p>
  </div>
</section>

<script>
  (function () {
    const busca = document.getElementById('filtroBusca');
    const especie = document.getElementById('filtroEspecie');
    if (!busca || !especie) return;

    const cards = Array.from(document.querySelectorAll('#listaCasos .caso'));
    const semResultados = document.getElementById('semResultados');

    function aplicarFiltro() {
      const termo = busca.value.trim().toLowerCase();
      const esp = especie.value;
      let visiveis = 0;

      cards.forEach((card) => {
        const nome = card.dataset.nome || '';
        const local = card.dataset.local || '';
        const cardEspecie = card.dataset.especie || '';
        const bateBusca = !termo || nome.includes(termo) || local.includes(termo);
        const bateEspecie = !esp || cardEspecie === esp;
        const mostrar = bateBusca && bateEspecie;
        card.hidden = !mostrar;
        if (mostrar) visiveis++;
      });

      semResultados.hidden = visiveis !== 0;
    }

    busca.addEventListener('input', aplicarFiltro);
    especie.addEventListener('change', aplicarFiltro);
  })();
</script>

<?php partial('footer'); ?>
