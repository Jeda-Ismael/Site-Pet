<?php
class Usuario {
    private $conn;

    // Construtor recebe a conexão com o banco de dados
    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'pet');
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    // Registrar novo usuário
    public function registrar($nome, $email, $senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        return $stmt->execute();
    }

    // Fazer login
    public function login($email, $senha) {
        $stmt = $this->conn->prepare("SELECT id, nome, senha FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                // login OK — salvar na sessão
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nome'] = $user['nome'];
                return true;
            }
        }
        return false;
    }

    // Redefinir senha
    public function redefinirSenha($email, $nova_senha) {
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("UPDATE usuario SET senha = ? WHERE email = ?");
        $stmt->bind_param("ss", $nova_senha_hash, $email);
        return $stmt->execute();
    }

    // Verificar se o email existe
    public function emailExiste($email) {
        $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Encerrar sessão (logout)
    public function logout() {
        session_start();
        session_destroy();
    }
}
?>
