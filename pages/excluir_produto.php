<?php
session_start();

include("conexao.php");

if (!isset($_SESSION["id"])) {
    header("Location: index.php"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}
?>

<!DOCTYPE html>
<html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Excluir Produto</h1>

        <?php

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $produto_id = $_GET['id'];

            // Consulta para obter os detalhes do produto, incluindo o $lista_id
            $sql = "SELECT nome_produto, quantidade, preco, lista_id FROM itens WHERE id = $produto_id";
            $result = $conexao->query($sql);
            $row = $result->fetch_assoc();
            $lista_id = $row['lista_id'];


            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar_exclusao"])) {
                $sql = "DELETE FROM itens WHERE id = $produto_id";
                if ($conexao->query($sql) === TRUE) {
                    echo "Produto excluído com sucesso!";
                    header("Location: ver_lista.php?id=$lista_id");
                    exit();
                } else {
                    echo "Erro ao excluir o produto: " . $conexao->error;
                }
            }

            // Consulta para obter o ID da lista à qual o produto pertence
            $sql = "SELECT lista_id FROM itens WHERE id = $produto_id";
            $result = $conexao->query($sql);
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $lista_id = $row['lista_id'];
            } else {
                echo "Produto não encontrado.";
                exit();
            }
        ?>

            <p>Tem certeza de que deseja excluir este produto?</p>
            <form action="" method="POST">
                <button type="submit" name="confirmar_exclusao" class="btn btn-danger">Sim, Excluir</button>
                <a href="ver_lista.php?id=<?php echo $lista_id; ?>" class="btn btn-primary">Cancelar</a>
            </form>

        <?php
            $conexao->close();
        } else {
            echo "ID de produto inválido.";
        }
        ?>
    </div>

</body>

</html>