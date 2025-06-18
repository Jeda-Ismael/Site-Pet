<?php
session_start();
$uid = $_SESSION['usuario_id'] ?? null;
$id = intval($_GET['id'] ?? 0);
if (!$uid) die("Acesso negado.");

$conn = new mysqli('localhost','root','','pet');
$stmt = $conn->prepare("SELECT imagem FROM adocoes WHERE id=? AND usuario_id=?");
$stmt->bind_param("ii",$id,$uid);
$stmt->execute(); $res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  if (file_exists($row['imagem'])) unlink($row['imagem']);
  $del = $conn->prepare("DELETE FROM adocoes WHERE id=? AND usuario_id=?");
  $del->bind_param("ii",$id,$uid);
  $del->execute();
}
header("Location: adocao.php");
