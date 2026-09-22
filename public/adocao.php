<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';

$usuarioId = current_user_id();
$stmt = Database::connection()->prepare("SELECT * FROM adocoes WHERE status = 'disponivel' ORDER BY data_cadastro DESC");
$stmt->execute();
$adocoes = $stmt->fetchAll();

$especies = array_values(array_unique(array_filter(array_map(fn($a) => $a['especie'], $adocoes))));
sort($especies);

partial('header', ['title' => 'Pets para Adoção']);
?>

<section class="section">
  <div class="section-header">
    <h2>Pets para Adoção</h2>
    <a href="<?= is_logged_in() ? 'cadastro_adocao.php' : 'loginpage.php'; ?>" class="btn-icon purple">
      <i class="fas fa-plus"></i> Colocar para adoção
    </a>
  </div>

  <?php if (!empty($adocoes)): ?>
  <div class="filters-bar">
    <div class="filter-search">
      <i class="fas fa-search"></i>
      <input type="text" id="filtroBusca" placeholder="Buscar por nome do pet...">
    </div>
    <select id="filtroEspecie">
      <option value="">Todas as espécies</option>
      <?php foreach ($especies as $esp): ?>
        <option value="<?= e($esp) ?>"><?= e($esp) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>

  <div class="casos" id="listaAdocoes">
    <?php if (empty($adocoes)): ?>
      <p class="empty-state"><i class="fas fa-heart"></i> Nenhum pet disponível para adoção no momento.</p>
    <?php endif; ?>
    <?php foreach ($adocoes as $r): ?>
      <div class="caso"
           data-nome="<?= e(mb_strtolower($r['nome_pet'])) ?>"
           data-especie="<?= e($r['especie']) ?>">
        <div class="caso-img-wrap">
          <img src="<?= e($r['imagem']) ?>" alt="Imagem do pet" onerror="this.style.display='none';this.nextElementSibling.classList.add('show')" onload="if(this.naturalWidth<20||this.naturalHeight<20){this.style.display='none';this.nextElementSibling.classList.add('show')}">
          <span class="caso-img-fallback"><i class="fas fa-paw"></i></span>
          <span class="caso-badge"><?= e($r['especie']) ?></span>
        </div>
        <h3><?= e($r['nome_pet']) ?></h3>
        <div class="caso-tags">
          <?php if (!empty($r['porte'])): ?><span class="tag"><i class="fas fa-ruler"></i> <?= e($r['porte']) ?></span><?php endif; ?>
          <?php if (!empty($r['sexo'])): ?><span class="tag"><i class="fas fa-<?= $r['sexo'] === 'Macho' ? 'mars' : 'venus' ?>"></i> <?= e($r['sexo']) ?></span><?php endif; ?>
          <?php if (!empty($r['cor'])): ?><span class="tag"><i class="fas fa-palette"></i> <?= e($r['cor']) ?></span><?php endif; ?>
        </div>
        <?php if (!empty($r['raca'])): ?><p><strong>Raça:</strong> <?= e($r['raca']) ?></p><?php endif; ?>
        <p><strong>Idade:</strong> <?= e($r['idade']) ?></p>
        <p><strong>Descrição:</strong> <?= nl2br(e($r['descricao'])) ?></p>

        <?php if ($usuarioId && (int) $r['usuario_id'] === $usuarioId): ?>
          <div class="acoes">
            <a href="editar_adocao.php?id=<?= (int) $r['id'] ?>" class="btn-icon purple">✏️ Editar</a>
            <form action="marcar_adotado.php" method="POST" onsubmit="return confirm('Marcar este pet como adotado? Ele sairá da lista de disponíveis.');" style="display:contents">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
              <button type="submit" class="btn-icon found">🎉 Adotado</button>
            </form>
            <form action="excluir_adocao.php" method="POST" onsubmit="return confirm('Confirmar exclusão?');" style="display:contents">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
              <button type="submit" class="btn-icon outline">🗑️ Excluir</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <p class="empty-state" id="semResultados" hidden><i class="fas fa-magnifying-glass"></i> Nenhum pet encontrado com esse filtro.</p>
  </div>
</section>

<script>
  (function () {
    const busca = document.getElementById('filtroBusca');
    const especie = document.getElementById('filtroEspecie');
    if (!busca || !especie) return;

    const cards = Array.from(document.querySelectorAll('#listaAdocoes .caso'));
    const semResultados = document.getElementById('semResultados');

    function aplicarFiltro() {
      const termo = busca.value.trim().toLowerCase();
      const esp = especie.value;
      let visiveis = 0;

      cards.forEach((card) => {
        const nome = card.dataset.nome || '';
        const cardEspecie = card.dataset.especie || '';
        const bateBusca = !termo || nome.includes(termo);
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
