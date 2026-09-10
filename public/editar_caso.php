<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require_login();

$usuarioId = current_user_id();
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    die('ID inválido.');
}

$stmt = Database::connection()->prepare('SELECT * FROM casos WHERE id = ? AND usuario_id = ?');
$stmt->execute([$id, $usuarioId]);
$caso = $stmt->fetch();

if (!$caso) {
    die('Caso não encontrado ou acesso não autorizado.');
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
    $nomeTutor = trim($_POST['nome_tutor'] ?? '');
    $contato = trim($_POST['contato'] ?? '');
    $local = trim($_POST['local'] ?? '');
    $data = $_POST['data'] ?? '';
    $descricao = trim($_POST['descricao'] ?? '');

    try {
        $imagem = $caso['imagem'];
        if (!empty($_FILES['imagem']['name'])) {
            $imagem = handle_image_upload($_FILES['imagem'], __DIR__ . '/uploads');
        }

        $stmt = Database::connection()->prepare(
            'UPDATE casos SET especie=?, nome_pet=?, nome_tutor=?, contato=?, local=?, data_cadastro=?, descricao=?, imagem=?, raca=?, porte=?, cor=?, sexo=?
             WHERE id=? AND usuario_id=?'
        );
        $stmt->execute([$especie, $nomePet, $nomeTutor, $contato, $local, $data, $descricao, $imagem, $raca, $porte, $cor, $sexo, $id, $usuarioId]);

        header('Location: casos.php');
        exit;
    } catch (RuntimeException $e) {
        $erro = $e->getMessage();
    } catch (PDOException $e) {
        $erro = 'Erro ao atualizar o caso.';
    }
}

$v = fn(string $k) => e($_POST[$k] ?? (string) ($caso[$k] ?? ''));

partial('header', ['title' => 'Editar Caso']);
?>

<section class="section">
  <h2>Editar caso</h2>

  <div class="form-container form-container-wide">
    <?php if ($erro): ?>
      <p class="erro"><?= e($erro) ?></p>
    <?php endif; ?>

    <form action="editar_caso.php" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int) $caso['id'] ?>">

      <div class="photo-field">
        <label for="imagemInput" class="photo-dropzone has-image" id="photoDropzone">
          <?php if (!empty($caso['imagem']) && file_exists(__DIR__ . '/' . $caso['imagem'])): ?>
            <img id="photoPreviewImg" class="photo-preview-img" src="<?= e($caso['imagem']) ?>" alt="Prévia da foto">
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
              <option value="<?= $opt ?>" <?= ($_POST['porte'] ?? $caso['porte'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Sexo <span class="opt">(opcional)</span></label>
          <select name="sexo">
            <option value="">Selecione</option>
            <?php foreach (['Macho', 'Fêmea'] as $opt): ?>
              <option value="<?= $opt ?>" <?= ($_POST['sexo'] ?? $caso['sexo'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="full">
          <label>Cor <span class="opt">(opcional)</span></label>
          <input name="cor" type="text" value="<?= $v('cor') ?>">
        </div>

        <div>
          <label>Nome do tutor</label>
          <input name="nome_tutor" type="text" value="<?= $v('nome_tutor') ?>" required>
        </div>
        <div>
          <label>Contato</label>
          <input name="contato" type="tel" value="<?= $v('contato') ?>" required>
        </div>

        <div>
          <label>Local de desaparecimento</label>
          <input name="local" type="text" value="<?= $v('local') ?>" required>
        </div>
        <div>
          <label>Data de desaparecimento</label>
          <input type="date" name="data" value="<?= e($_POST['data'] ?? $caso['data_cadastro']) ?>" required>
        </div>

        <div class="full">
          <label>Descrição</label>
          <textarea name="descricao" required><?= $v('descricao') ?></textarea>
        </div>
      </div>

      <button type="submit" class="btn-icon purple">💾 Atualizar</button>
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
