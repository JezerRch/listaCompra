<?php

include("conexao.php");
$emailCadastrado = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Validação de Dados
    if (empty($nome) || empty($email) || empty($_POST["senha"])) {
        echo '<script>alert("Preencha todos os campos!");</script>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailCadastrado = "Formato de e-mail inválido.";
    } else {

        // Verificar se o e-mail já está cadastrado
        $verificar_sql = "SELECT id FROM usuarios WHERE email = ?";
        $verificar_stmt = $conexao->prepare($verificar_sql);
        $verificar_stmt->bind_param("s", $email);
        $verificar_stmt->execute();
        $verificar_result = $verificar_stmt->get_result();

        if ($verificar_result->num_rows > 0) {
            $emailCadastrado = "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
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
                    $cadastroRealiadoSucesso = "Cadastro realizado com sucesso!";
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

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="text-center">Cadastrar</h1>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                    <?php echo '<small class="text-danger">' . $emailCadastrado . '</small>'; ?>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" class="form-control" name="senha" required>
                </div>

                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-secondary btn-block" href="index.php">Voltar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>