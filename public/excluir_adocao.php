<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Método não permitido.');
}

csrf_verify();

$usuarioId = current_user_id();
$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    die('ID inválido.');
}

$conn = Database::connection();

$stmt = $conn->prepare('SELECT imagem FROM adocoes WHERE id=? AND usuario_id=?');
$stmt->execute([$id, $usuarioId]);
$row = $stmt->fetch();

if ($row) {
    if (!empty($row['imagem']) && file_exists(__DIR__ . '/' . $row['imagem'])) {
        unlink(__DIR__ . '/' . $row['imagem']);
    }
    $del = $conn->prepare('DELETE FROM adocoes WHERE id=? AND usuario_id=?');
    $del->execute([$id, $usuarioId]);
}

header('Location: adocao.php');
exit;
