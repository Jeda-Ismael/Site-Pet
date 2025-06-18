<?php
require_once 'config/Database.php'; // ajuste o caminho se precisar
require_once 'login/Usuario.php';    // ajuste o caminho se precisar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];

    $usuario = new Usuario();

    if ($usuario->redefinirSenha($email, $nova_senha)) {
        echo "Senha redefinida com sucesso. <a href='loginpage.php'>Fazer login</a>";
    } else {
        echo "Erro ao redefinir senha. Verifique o email informado.";
    }
} else {
    header("Location: recuperar_senha.php");
    exit;
}
?>
