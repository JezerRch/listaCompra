<?php

include("conexao.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Validação de Dados
    if (empty($nome) || empty($email) || empty($_POST["senha"])) {
        echo "Por favor, preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de e-mail inválido.";
    } else {

        // Verificar se o e-mail já está cadastrado
        $verificar_sql = "SELECT id FROM usuarios WHERE email = ?";
        $verificar_stmt = $conexao->prepare($verificar_sql);
        $verificar_stmt->bind_param("s", $email);
        $verificar_stmt->execute();
        $verificar_result = $verificar_stmt->get_result();

        if ($verificar_result->num_rows > 0) {
            echo "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
        } else {
            // Inserir o novo usuário
            $inserir_sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $inserir_stmt = $conexao->prepare($inserir_sql);
            $inserir_stmt->bind_param("sss", $nome, $email, $senha);

            if ($inserir_stmt->execute()) {
                // Após inserir o novo usuário no banco de dados e obter o ID gerado automaticamente
                $usuario_id = $conexao->insert_id;

                // Criar uma lista de compras padrão para o novo usuário
                $lista_padrao_sql = "INSERT INTO listas (nome_lista, usuario_id) VALUES (?, ?)";
                $lista_padrao_stmt = $conexao->prepare($lista_padrao_sql);
                $nome_lista_padrao = "Minha Lista Padrão"; // Nome da lista padrão
                $lista_padrao_stmt->bind_param("si", $nome_lista_padrao, $usuario_id);

                if ($lista_padrao_stmt->execute()) {
                    echo "Cadastro realizado com sucesso!";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Erro ao criar a lista padrão: " . $lista_padrao_stmt->error;
                }
            } else {
                echo "Erro ao cadastrar: " . $inserir_stmt->error;
            }
        }

        $conexao->close();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Cadastro</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" name="senha" required>
            </div>

            <div class="mt-5 row">
                <div class="col-6">
                    <button type="submit" class="btn btn-primary col-12">Cadastrar</button>
                </div>
                <div class="col-6">
                    <a class="btn btn-primary col-12" href="index.php">Voltar</a>
                </div>
            </div>
        </form>
    </div>

</body>

</html>