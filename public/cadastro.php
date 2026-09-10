<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require_login();

$erro = null;
$v = fn(string $k) => e($_POST[$k] ?? '');

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
        $imagem = handle_image_upload($_FILES['imagem'] ?? [], __DIR__ . '/uploads');

        $stmt = Database::connection()->prepare(
            'INSERT INTO casos (usuario_id, especie, nome_pet, nome_tutor, contato, local, data_cadastro, descricao, imagem, raca, porte, cor, sexo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([current_user_id(), $especie, $nomePet, $nomeTutor, $contato, $local, $data, $descricao, $imagem, $raca, $porte, $cor, $sexo]);

        header('Location: casos.php');
        exit;
    } catch (RuntimeException $e) {
        $erro = $e->getMessage();
    } catch (PDOException $e) {
        $erro = 'Erro ao salvar o caso. Tente novamente.';
    }
}

partial('header', ['title' => 'Cadastrar Caso']);
?>

<section class="section">
  <h2>Cadastro de caso</h2>

  <div class="form-container form-container-wide">
    <?php if ($erro): ?>
      <p class="erro"><?= e($erro) ?></p>
    <?php endif; ?>
    <form action="cadastro.php" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="photo-field">
        <label for="imagemInput" class="photo-dropzone" id="photoDropzone">
          <img id="photoPreviewImg" class="photo-preview-img" alt="Prévia da foto" hidden>
          <span class="photo-dropzone-hint" id="photoHint">
            <i class="fas fa-camera"></i>
            Clique para adicionar uma foto do pet
          </span>
        </label>
        <input type="file" id="imagemInput" name="imagem" accept="image/*" required hidden>
      </div>

      <div class="form-grid">
        <div class="full">
          <label>Nome do pet</label>
          <input name="nome_pet" type="text" placeholder="Ex: Rex" value="<?= $v('nome_pet') ?>" required>
        </div>

        <div>
          <label>Espécie</label>
          <input name="especie" type="text" placeholder="Ex: Cachorro" value="<?= $v('especie') ?>" required>
        </div>
        <div>
          <label>Raça <span class="opt">(opcional)</span></label>
          <input name="raca" type="text" placeholder="Ex: Labrador" value="<?= $v('raca') ?>">
        </div>

        <div>
          <label>Porte <span class="opt">(opcional)</span></label>
          <select name="porte">
            <option value="">Selecione</option>
            <option value="Pequeno" <?= ($_POST['porte'] ?? '') === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
            <option value="Médio" <?= ($_POST['porte'] ?? '') === 'Médio' ? 'selected' : '' ?>>Médio</option>
            <option value="Grande" <?= ($_POST['porte'] ?? '') === 'Grande' ? 'selected' : '' ?>>Grande</option>
          </select>
        </div>
        <div>
          <label>Sexo <span class="opt">(opcional)</span></label>
          <select name="sexo">
            <option value="">Selecione</option>
            <option value="Macho" <?= ($_POST['sexo'] ?? '') === 'Macho' ? 'selected' : '' ?>>Macho</option>
            <option value="Fêmea" <?= ($_POST['sexo'] ?? '') === 'Fêmea' ? 'selected' : '' ?>>Fêmea</option>
          </select>
        </div>

        <div class="full">
          <label>Cor <span class="opt">(opcional)</span></label>
          <input name="cor" type="text" placeholder="Ex: Caramelo com manchas brancas" value="<?= $v('cor') ?>">
        </div>

        <div>
          <label>Nome do tutor</label>
          <input name="nome_tutor" type="text" placeholder="Seu nome" value="<?= $v('nome_tutor') ?>" required>
        </div>
        <div>
          <label>Contato</label>
          <input name="contato" type="tel" placeholder="Telefone / WhatsApp" value="<?= $v('contato') ?>" required>
        </div>

        <div>
          <label>Local de desaparecimento</label>
          <input name="local" type="text" placeholder="Ex: Praça Central" value="<?= $v('local') ?>" required>
        </div>
        <div>
          <label>Data de desaparecimento</label>
          <input name="data" type="date" value="<?= $v('data') ?>" required>
        </div>

        <div class="full">
          <label>Descrição</label>
          <textarea name="descricao" placeholder="Características, comportamento, qualquer detalhe que ajude a identificar o pet" required><?= $v('descricao') ?></textarea>
        </div>
      </div>

      <button type="submit" class="btn-icon purple">
        <i class="fas fa-file-alt"></i> Registrar
      </button>
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
