<?php

if (!isset($_SESSION["id"])) {
    header("Location: login"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}
?>

<div class="container mt-5">

    <form action="" method="POST">
        <h2>Adicionar Produto</h2>
        <div class="row">
            <div class="form-group col-lg-3">
                <input type="text" class="form-control" name="nome_produto" placeholder="Nome do Produto" required>
            </div>
            <div class="form-group col-lg-3">
                <input type="number" class="form-control" name="quantidade" placeholder="Quantidade" required>
            </div>
            <div class="form-group col-lg-3">
                <input type="number" step="0.01" class="form-control" name="preco" placeholder="Preço (R$)" required>
            </div>
            <div class="form-group col-lg-3">
                <button type="submit" name="adicionar_produto" class="btn btn-primary">Adicionar Produto</button>
            </div>
        </div>
    </form>


    <br>
    <a class="btn btn-secondary" href="<?php echo INCLUDE_PATH; ?>dashboard">Voltar para a lista de compras</a>

    <hr class="m-5">


    <h1>Itens da Lista de Compras</h1>

    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $lista_id = $_GET['id'];

        // Verificar se a lista pertence ao usuário logado
        $usuario_id = $_SESSION["id"];
        $sql = "SELECT id FROM listas WHERE id = $lista_id AND usuario_id = $usuario_id";
        $result = $conexao->query($sql);

        if ($result->num_rows == 1) {
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["adicionar_produto"])) {
                $nome_produto = $_POST["nome_produto"];
                $quantidade = $_POST["quantidade"];
                $preco = $_POST["preco"];
                $usuario_id = $_SESSION["id"];
                $data_adicao = date("Y-m-d H:i:s"); // Obtém a data/hora atual

                $sql = "INSERT INTO itens (lista_id, nome_produto, quantidade, preco, usuario_id, data_adicao) 
                            VALUES ('$lista_id', '$nome_produto', '$quantidade', '$preco', '$usuario_id', '$data_adicao')";
                if ($conexao->query($sql) === TRUE) {
                    echo "Produto adicionado com sucesso!";
                    header("Location: ver_lista?id=$lista_id");
                    exit();
                } else {
                    echo "Erro ao adicionar o produto: " . $conexao->error;
                }
            }


            // Consulta para listar os itens da lista do usuário logado
            $sql = "SELECT id, nome_produto, quantidade, preco FROM itens WHERE lista_id = $lista_id";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Produto</th><th>Quantidade</th><th>Preço</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['nome_produto'] . "</td>";
                    echo "<td>" . $row['quantidade'] . "</td>";
                    echo "<td>R$ " . number_format($row['preco'], 2) . "</td>";
                    echo "<td>
                        <a href='" . INCLUDE_PATH . "editar_produto?id=" . $row['id'] . "' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Editar</a> 
                        <a href='" . INCLUDE_PATH . "excluir_produto?id=" . $row['id'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> Excluir</a>
                    </td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "Nenhum item encontrado nesta lista.";
            }


            // Calcular o valor total
            $sql = "SELECT SUM(quantidade * preco) AS total FROM itens WHERE lista_id = $lista_id";
            $result = $conexao->query($sql);
            $row = $result->fetch_assoc();
            $total = $row['total'];
    ?>
            <h3>Valor Total: R$ <?php echo number_format($total, 2); ?></h3>
    <?php
        } else {
            echo "Esta lista não pertence ao usuário logado.";
        }

        $conexao->close();
    } else {
        echo "ID de lista inválido.";
    }

    ?>
</div>