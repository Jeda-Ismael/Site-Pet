<?php
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    die("Acesso negado.");
}

// Verifica se o ID foi passado
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID inválido.");
}

// Conexão com o banco
$conn = new mysqli('localhost', 'root', '', 'pet');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar imagem para excluir do diretório
$stmt = $conn->prepare("SELECT imagem FROM casos WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $caso = $result->fetch_assoc();
    if (!empty($caso['imagem']) && file_exists($caso['imagem'])) {
        unlink($caso['imagem']); // exclui o arquivo da pasta
    }

    // Excluir do banco
    $delete = $conn->prepare("DELETE FROM casos WHERE id = ? AND usuario_id = ?");
    $delete->bind_param("ii", $id, $usuario_id);
    $delete->execute();
}

$conn->close();
header("Location: casos.php");
exit;
