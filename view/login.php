<?php
session_start();

require_once 'conexao.php';

if(isset($_SESSION['usuario_id'])){
    header("Location: home.php");
    exit;
}

$mensagem = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if($resultado->num_rows == 1){

        $usuario = $resultado->fetch_assoc();

        if(password_verify($senha, $usuario['senha'])){

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            header("Location: home.php");
            exit;
        }
    }

    $mensagem = "Usuário ou senha inválidos.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h1>Sistema de Login</h1>

<?php
if(!empty($mensagem)){
    echo "<p>$mensagem</p>";
}
?>

<form method="POST">

    <label>E-mail:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Entrar</button>

</form>

<br>

<a href="cadastro.php">Cadastrar-se</a>

<br><br>

<a href="recuperar_senha.php">Esqueci minha senha</a>

</body>
</html>