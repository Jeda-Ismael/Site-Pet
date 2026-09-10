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

$stmt = $conn->prepare('SELECT imagem FROM casos WHERE id = ? AND usuario_id = ?');
$stmt->execute([$id, $usuarioId]);
$caso = $stmt->fetch();

if ($caso) {
    if (!empty($caso['imagem']) && file_exists(__DIR__ . '/' . $caso['imagem'])) {
        unlink(__DIR__ . '/' . $caso['imagem']);
    }

    $delete = $conn->prepare('DELETE FROM casos WHERE id = ? AND usuario_id = ?');
    $delete->execute([$id, $usuarioId]);
}

header('Location: casos.php');
exit;
