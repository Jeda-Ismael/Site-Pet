<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require_login();

$usuarioId = current_user_id();
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    die('ID inválido.');
}

$stmt = Database::connection()->prepare('SELECT * FROM adocoes WHERE id=? AND usuario_id=?');
$stmt->execute([$id, $usuarioId]);
$adocao = $stmt->fetch();

if (!$adocao) {
    die('Pet não encontrado ou sem permissão.');
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $especie = trim($_POST['especie'] ?? '');
    $raca = trim($_POST['raca'] ?? '') ?: null;
    $porte = trim($_POST['porte'] ?? '') ?: null;
    $cor = trim($_POST['cor'] ?? '') ?: null;
    $sexo = trim($_POST['sexo'] ?? '') ?: null;
    $nomePet = trim($_POST['nome_pet'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    try {
        $imagem = $adocao['imagem'];
        if (!empty($_FILES['imagem']['name'])) {
            $imagem = handle_image_upload($_FILES['imagem'], __DIR__ . '/uploads');
        }

        $stmt = Database::connection()->prepare(
            'UPDATE adocoes SET especie=?, nome_pet=?, idade=?, descricao=?, imagem=?, raca=?, porte=?, cor=?, sexo=?
             WHERE id=? AND usuario_id=?'
        );
        $stmt->execute([$especie, $nomePet, $idade, $descricao, $imagem, $raca, $porte, $cor, $sexo, $id, $usuarioId]);

        header('Location: adocao.php');
        exit;
    } catch (RuntimeException $e) {
        $erro = $e->getMessage();
    } catch (PDOException $e) {
        $erro = 'Erro ao atualizar.';
    }
}

$v = fn(string $k) => e($_POST[$k] ?? (string) ($adocao[$k] ?? ''));

partial('header', ['title' => 'Editar Pet para Adoção']);
?>

<section class="section">
  <h2>Editar Pet para Adoção</h2>

  <div class="form-container form-container-wide">
    <?php if ($erro): ?>
      <p class="erro"><?= e($erro) ?></p>
    <?php endif; ?>

    <form action="editar_adocao.php" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int) $adocao['id'] ?>">

      <div class="photo-field">
        <label for="imagemInput" class="photo-dropzone has-image" id="photoDropzone">
          <?php if (!empty($adocao['imagem']) && file_exists(__DIR__ . '/' . $adocao['imagem'])): ?>
            <img id="photoPreviewImg" class="photo-preview-img" src="<?= e($adocao['imagem']) ?>" alt="Prévia da foto">
            <span class="photo-dropzone-hint" id="photoHint" hidden><i class="fas fa-camera"></i> Clique para trocar a foto</span>
          <?php else: ?>
            <img id="photoPreviewImg" class="photo-preview-img" alt="Prévia da foto" hidden>
            <span class="photo-dropzone-hint" id="photoHint"><i class="fas fa-camera"></i> Clique para adicionar uma foto do pet</span>
          <?php endif; ?>
        </label>
        <input type="file" id="imagemInput" name="imagem" accept="image/*" hidden>
      </div>

      <div class="form-grid">
        <div class="full">
          <label>Nome do pet</label>
          <input name="nome_pet" type="text" value="<?= $v('nome_pet') ?>" required>
        </div>

        <div>
          <label>Espécie</label>
          <input name="especie" type="text" value="<?= $v('especie') ?>" required>
        </div>
        <div>
          <label>Raça <span class="opt">(opcional)</span></label>
          <input name="raca" type="text" value="<?= $v('raca') ?>">
        </div>

        <div>
          <label>Porte <span class="opt">(opcional)</span></label>
          <select name="porte">
            <option value="">Selecione</option>
            <?php foreach (['Pequeno', 'Médio', 'Grande'] as $opt): ?>
              <option value="<?= $opt ?>" <?= ($_POST['porte'] ?? $adocao['porte'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Sexo <span class="opt">(opcional)</span></label>
          <select name="sexo">
            <option value="">Selecione</option>
            <?php foreach (['Macho', 'Fêmea'] as $opt): ?>
              <option value="<?= $opt ?>" <?= ($_POST['sexo'] ?? $adocao['sexo'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label>Cor <span class="opt">(opcional)</span></label>
          <input name="cor" type="text" value="<?= $v('cor') ?>">
        </div>
        <div>
          <label>Idade</label>
          <input name="idade" type="text" value="<?= $v('idade') ?>" required>
        </div>

        <div class="full">
          <label>Descrição</label>
          <textarea name="descricao" required><?= $v('descricao') ?></textarea>
        </div>
      </div>

      <button type="submit" class="btn-icon purple">💾 Salvar Alterações</button>
    </form>
  </div>
</section>

<script>
  (function () {
    const input = document.getElementById('imagemInput');
    const img = document.getElementById('photoPreviewImg');
    const hint = document.getElementById('photoHint');
    const dropzone = document.getElementById('photoDropzone');

    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        img.src = e.target.result;
        img.hidden = false;
        hint.hidden = true;
        dropzone.classList.add('has-image');
      };
      reader.readAsDataURL(file);
    });
  })();
</script>

<?php partial('footer'); ?>
