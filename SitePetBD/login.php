<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Conexão
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'pet';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

// Recebendo dados
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Buscar usuário
$sql = "SELECT id, nome, senha FROM usuario WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  
  if (password_verify($senha, $user['senha'])) {
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['usuario_nome'] = $user['nome'];

    // ✅ Redireciona após login com sucesso
    header("Location: index.php");
    exit;
  } else {
    echo "❌ Senha incorreta.";
  }
} else {
  echo "❌ E-mail não encontrado.";
}

$stmt->close();
$conn->close();
?>
