<?php

if (!isset($_SESSION["id"])) {
    header("Location: index"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}
?>


<div class="container mt-5">
    <h1 class="mb-4">Editar Lista de Compras</h1>

    <?php
    // Verificar se foi fornecido um ID de lista válido na URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {

        // Obter o ID da lista da URL
        $lista_id = $_GET['id'];

        // Consulta para obter o nome atual da lista
        $sql = "SELECT nome_lista FROM listas WHERE id = $lista_id";
        $result = $conexao->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $nome_lista = $row['nome_lista'];
        } else {
            echo "Lista não encontrada.";
            exit();
        }

        // Processar o formulário de edição
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $novo_nome_lista = $_POST["novo_nome_lista"];
            $sql = "UPDATE listas SET nome_lista = '$novo_nome_lista' WHERE id = $lista_id";
            if ($conexao->query($sql) === TRUE) {
                echo '<div class="alert alert-success" role="alert">Nome da lista atualizado com sucesso!</div>';
                $nome_lista = $novo_nome_lista;
                header("Location: dashboard"); // Redireciona para o dashboard.php
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erro ao atualizar o nome da lista: ' . $conexao->error . '</div>';
            }
        }
    ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="novo_nome_lista">Novo Nome da Lista:</label>
                <input type="text" class="form-control" name="novo_nome_lista" value="<?php echo $nome_lista; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Nome</button>
        </form>

        <div class="mt-3">
            <a class="btn btn-secondary" href="dashboard">Voltar para a lista de compras</a>
        </div>

    <?php
        // Fechar a conexão
        $conexao->close();
    } else {
        echo "ID de lista inválido.";
    }
    ?>
</div>