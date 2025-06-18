<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'pet';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se os dados vieram via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST['nome'] ?? '';
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';
  $confirma_senha = $_POST['confirma_senha'] ?? '';

  // Verifica se as senhas coincidem
  if ($senha !== $confirma_senha) {
    die("❌ As senhas não conferem!");
  }

  // Criptografar senha
  $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

  $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $nome, $email, $senha_hash);

  if ($stmt->execute()) {
    // Redireciona após sucesso
    header("Location: loginpage.php");
    exit;
  } else {
    echo "❌ Erro ao salvar: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();
?>
