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

$stmt = Database::connection()->prepare("UPDATE adocoes SET status = 'adotado' WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $usuarioId]);

header('Location: adocao.php');
exit;
