<?php

if (!isset($_SESSION["id"])) {
    header("Location: login"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}

$usuario_id = $_SESSION["id"]; // Obtém o ID do usuário logado a partir da sessão

// // Conectar ao banco de dados (substitua as informações conforme necessário)
// $host = "seu_host";
// $usuario_bd = "seu_usuario";
// $senha_bd = "sua_senha";
// $nome_bd = "seu_banco";

// $conexao = new mysqli($host, $usuario_bd, $senha_bd, $nome_bd);

// // Verificar a conexão
// if ($conexao->connect_error) {
//     die("Conexão falhou: " . $conexao->connect_error);
// }
function senha_atual_correta($conexao, $usuario_id, $senha_atual)
{
    // Consulta para obter a senha atual do usuário
    $sql = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se a consulta foi bem-sucedida e se há uma linha correspondente
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senha_hash = $row['senha'];

        // Verifica se a senha fornecida corresponde à senha armazenada no banco de dados
        if (password_verify($senha_atual, $senha_hash)) {
            return true; // Senha correta
        }
    }

    return false; // Senha incorreta ou usuário não encontrado
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $msgErro = '';

    // Verificar se a senha atual do usuário está correta (você pode implementar essa lógica conforme necessário)
    if (senha_atual_correta($conexao, $usuario_id, $senha_atual)) {
        // Verificar se a nova senha e a confirmação coincidem
        if ($nova_senha === $confirma_senha) {
            // Atualizar a senha no banco de dados
            $senha_hashed = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql_update_senha = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $stmt_update_senha = $conexao->prepare($sql_update_senha);
            $stmt_update_senha->bind_param("si", $senha_hashed, $usuario_id);

            if ($stmt_update_senha->execute()) {
                echo "Senha alterada com sucesso!";
            } else {
                echo "Erro ao alterar a senha: " . $stmt_update_senha->error;
            }
        } else {
            echo "A nova senha e a confirmação não coincidem.";
        }
    } else {
        echo "Senha atual incorreta.";
    }
}

// Atualizar nome e email no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $novo_nome = $_POST['novo_nome'];
    $novo_email = $_POST['novo_email'];

    // Atualizar o nome e o email no banco de dados
    $sql_update = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
    $stmt_update = $conexao->prepare($sql_update);
    $stmt_update->bind_param("ssi", $novo_nome, $novo_email, $usuario_id);

    if ($stmt_update->execute()) {
        echo "Perfil atualizado com sucesso!";
        $_SESSION["nome"] = $novo_nome; // Atualizar o nome na sessão também, se necessário
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt_update->error;
    }
}

// Buscar nome e email do usuário no banco de dados
$sql_select = "SELECT id, nome, email FROM usuarios WHERE id = ?";
$stmt_select = $conexao->prepare($sql_select);
$stmt_select->bind_param("i", $usuario_id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();

if ($result_select->num_rows > 0) {
    $row_select = $result_select->fetch_assoc();
    $nome_usuario = $row_select['nome'];
    $email_usuario = $row_select['email'];
?>

    <style>
        .minha-conta-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 10px;
        }

        .minha-conta-section h2 {
            font-size: 1.5rem;
            color: #333;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 0;
        }

        .account-actions a {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 16px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: 1px solid #007bff;
            border-radius: 5px;
        }

        .account-actions a:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>

    <div class="container">
        <div class="minha-conta-section">

            <h2>Minha Conta</h2>
            <div class="user-info">
                <p><strong>Nome:</strong> <?php echo $nome_usuario; ?></p>
                <p><strong>Email:</strong> <?php echo $email_usuario; ?></p>
            </div>
            <div class="account-actions">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarModal">
                    Editar
                </button>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#alterarSenhaModal">
                    Alterar Senha
                </button>

                <a type="button" href="dashboard" class="btn btn-primary float-right">Voltar</a>

                <!-- Modal editar perfil-->
                <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarModalLabel">Editar perfil</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Formulário para atualizar perfil -->
                                <form method="post" action="">
                                    <div class="form-group">
                                        <input placeholder="Nome completo" type="text" class="form-control" id="novo_nome" name="novo_nome" required value="<?php echo $nome_usuario; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="E-mail" type="email" class="form-control" id="novo_email" name="novo_email" required value="<?php echo $email_usuario; ?>">
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary">Atualizar Perfil</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Alteração de Senha -->
                <div class="modal fade" id="alterarSenhaModal" tabindex="-1" role="dialog" aria-labelledby="alterarSenhaModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="alterarSenhaModalLabel">Alterar Senha</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Formulário para alterar senha -->
                                <form method="post" action="">
                                    <div class="form-group">
                                        <input placeholder="Senha atual" type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Nova senha" type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Confirme nova senha" type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                                    </div>
                                    <button type="submit" name="alterar_senha" class="btn btn-warning">Salvar Senha</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <?php
} else {
    echo "Nenhum registro encontrado para o usuário.";
}


// Fechar a conexão
$conexao->close();

    ?>