<?php
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? null;


$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'pet'; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
  die("Acesso negado. Faça login primeiro.");
}

$especie = $_POST['especie'];
$nome_pet = $_POST['nome_pet'];
$nome_tutor = $_POST['nome_tutor'];
$contato = $_POST['contato'];
$local = $_POST['local'];
$data = $_POST['data'];
$descricao = $_POST['descricao'];

$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

$foto = $_FILES['imagem'];
$nomeArquivo = time()."_".basename($foto['name']);
$destino = $uploadDir . $nomeArquivo;

if (move_uploaded_file($foto['tmp_name'], $destino)) {
    $sql = "INSERT INTO casos (especie, nome_pet, nome_tutor, contato, local, data_cadastro, descricao, imagem, usuario_id)
        VALUES (?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", $especie, $nome_pet, $nome_tutor, $contato, $local, $data, $descricao, $destino, $usuario_id);

    
    if ($stmt->execute()) {
        header("Location: casos.php");
        exit;
    } else {
        echo "Erro BD: ".$stmt->error;
    }
} else {
    echo "Erro ao fazer upload da imagem.";
}

$conn->close();
?>
