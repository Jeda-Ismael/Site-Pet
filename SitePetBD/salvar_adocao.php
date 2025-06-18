<?php
session_start();
if (!isset($_SESSION['usuario_id'])) die("Acesso negado.");

$conn = new mysqli('localhost','root','','pet');
if ($conn->connect_error) die("Erro DB: " . $conn->connect_error);

$usuario = $_SESSION['usuario_id'];
$e = $_POST['especie'];
$n = $_POST['nome_pet'];
$i = $_POST['idade'];
$d = $_POST['descricao'];

$foto = $_FILES['imagem'];
if ($foto['error'] === UPLOAD_ERR_OK) {
  $dest = 'uploads/'.time().'_'.basename($foto['name']);
  move_uploaded_file($foto['tmp_name'],$dest);
} else {
  die("Erro no upload.");
}

$stmt = $conn->prepare("
  INSERT INTO adocoes(usuario_id,especie,nome_pet,idade,descricao,imagem)
  VALUES(?,?,?,?,?,?)
");
$stmt->bind_param("isssss",$usuario,$e,$n,$i,$d,$dest);
if ($stmt->execute()) header("Location: adocao.php");
else echo "Erro ao salvar: ".$stmt->error;
