<?php

require_once 'conexao.php';

$mensagem = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cpf = trim($_POST['cpf']);
    $data_nascimento = $_POST['data_nascimento'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if($nova_senha != $confirmar_senha){

        $mensagem = "As senhas não coincidem.";

    }else{

        $sql = "SELECT id
                FROM usuarios
                WHERE cpf = ?
                AND data_nascimento = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $cpf,
            $data_nascimento
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        if($resultado->num_rows == 1){

            $usuario = $resultado->fetch_assoc();

            $senhaHash = password_hash(
                $nova_senha,
                PASSWORD_DEFAULT
            );

            $update = $conn->prepare(
                "UPDATE usuarios
                 SET senha = ?
                 WHERE id = ?"
            );

            $update->bind_param(
                "si",
                $senhaHash,
                $usuario['id']
            );

            $update->execute();

            header("Location: login.php");
            exit;

        }else{

            $mensagem = "CPF ou data de nascimento inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
</head>
<body>

<h1>Recuperar Senha</h1>

<?php
if(!empty($mensagem)){
    echo "<p>$mensagem</p>";
}
?>

<form method="POST">

    <label>CPF:</label><br>
    <input type="text" name="cpf" required><br><br>

    <label>Data de Nascimento:</label><br>
    <input type="date" name="data_nascimento" required><br><br>

    <label>Nova Senha:</label><br>
    <input type="password" name="nova_senha" required><br><br>

    <label>Confirmar Nova Senha:</label><br>
    <input type="password" name="confirmar_senha" required><br><br>

    <button type="submit">
        Alterar Senha
    </button>

</form>

<br>

<a href="login.php">Voltar para Login</a>

</body>
</html>