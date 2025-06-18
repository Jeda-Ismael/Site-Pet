<?php
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) die("Acesso negado.");

$conn = new mysqli('localhost', 'root', '', 'pet');
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

// Recebe os dados
$id         = $_POST['id'] ?? null;
$especie    = $_POST['especie'] ?? '';
$nome_pet   = $_POST['nome_pet'] ?? '';
$nome_tutor = $_POST['nome_tutor'] ?? '';
$contato    = $_POST['contato'] ?? '';
$local      = $_POST['local'] ?? '';
$data       = $_POST['data'] ?? '';
$descricao  = $_POST['descricao'] ?? '';
$novaImagem = $_FILES['imagem']['tmp_name'];

// Atualização base
$sql = "UPDATE casos SET especie=?, nome_pet=?, nome_tutor=?, contato=?, local=?, data_cadastro=?, descricao=?";

// Se imagem enviada, adiciona campo imagem
if (!empty($novaImagem)) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $nomeImagem = $uploadDir . time() . "_" . basename($_FILES['imagem']['name']);
    move_uploaded_file($_FILES['imagem']['tmp_name'], $nomeImagem);
    $sql .= ", imagem=?";
}

// Condição de segurança
$sql .= " WHERE id=? AND usuario_id=?";

$stmt = $conn->prepare($sql);

// Liga os parâmetros 
if (!empty($novaImagem)) {
    $stmt->bind_param("sssssssssii", $especie, $nome_pet, $nome_tutor, $contato, $local, $data, $descricao, $nomeImagem, $id, $usuario_id);
} else {
    $stmt->bind_param("sssssssii", $especie, $nome_pet, $nome_tutor, $contato, $local, $data, $descricao, $id, $usuario_id);
}

// Executa gfadhadh
if ($stmt->execute()) {
    header("Location: casos.php");
    exit;
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
