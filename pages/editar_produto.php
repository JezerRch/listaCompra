<?php


include("conexao.php");

if (!isset($_SESSION["id"])) {
    header("Location: login"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}
?>


<div class="container mt-5">
    <h1>Editar Produto</h1>

    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $produto_id = $_GET['id'];

        // Consulta para obter os detalhes do produto, incluindo o $lista_id
        $sql = "SELECT nome_produto, quantidade, preco, lista_id FROM itens WHERE id = $produto_id";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $lista_id = $row['lista_id'];


        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_produto"])) {
            $nome_produto = $_POST["nome_produto"];
            $quantidade = $_POST["quantidade"];
            $preco = $_POST["preco"];

            $sql = "UPDATE itens SET nome_produto = '$nome_produto', quantidade = '$quantidade', preco = '$preco' WHERE id = $produto_id";
            if ($conexao->query($sql) === TRUE) {
                echo "Produto atualizado com sucesso!";
                header("Location: ver_lista?id=$lista_id");
                exit();
            } else {
                echo "Erro ao atualizar o produto: " . $conexao->error;
            }
        }

        // Consulta para obter os detalhes do produto
        $sql = "SELECT nome_produto, quantidade, preco, lista_id FROM itens WHERE id = $produto_id";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $lista_id = $row['lista_id'];
    ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nome_produto">Nome do Produto:</label>
                <input type="text" class="form-control" name="nome_produto" value="<?php echo $row['nome_produto']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" class="form-control" name="quantidade" value="<?php echo $row['quantidade']; ?>" required>
            </div>
            <div class="form-group">
                <label for="preco">Preço (R$):</label>
                <input type="number" step="0.01" class="form-control" name="preco" value="<?php echo $row['preco']; ?>" required>
            </div>
            <button type="submit" name="editar_produto" class="btn btn-primary">Atualizar Produto</button>
        </form>

        <br>
        <a class="btn btn-secondary" href="<?php echo INCLUDE_PATH; ?>ver_lista?id=<?php echo $lista_id; ?>">Voltar para a lista de itens</a>

    <?php
        $conexao->close();
    } else {
        echo "ID de produto inválido.";
    }
    ?>

</div>