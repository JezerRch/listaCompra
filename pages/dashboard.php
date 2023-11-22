<?php

if (!isset($_SESSION["id"])) {
    header("Location: login"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}

$usuario_id = $_SESSION["id"]; // Obtém o ID do usuário logado a partir da sessão

$sql = "SELECT id, nome_lista FROM listas WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);

?>

<div class="container mt-5">

    <div class="row">
        <div class="col-8">
            <h1 class="welcome-heading">Bem-vindo, <?php echo $_SESSION["nome"]; ?>!</h1>
        </div>
        <div class="col-4 text-right">
            <a href="<?php echo INCLUDE_PATH; ?>minha-conta" class="btn btn-success">Minha conta</a>
            <a href="<?php echo INCLUDE_PATH; ?>logout" class="btn btn-danger logout-btn">Sair</a>
        </div>
    </div>

    <div class="my-4">
        <button style="font-size: 15px;" class="btn btn-success" data-toggle="modal" data-target="#createListModal">
            NOVA LISTA
            <i style="font-size: 20px;" class="bi bi-file-earmark-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="createListModal" tabindex="-1" role="dialog" aria-labelledby="createListModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createListModalLabel">Criar nova lista de compra</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo INCLUDE_PATH; ?>criar_lista" method="POST">
                            <div class="form-group">
                                <input placeholder="Nome da lista" type="text" class="form-control" name="nome_lista" required>
                            </div>
                            <button type="submit" class="btn btn-success create-list-btn">Criar lista de compra</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h2 class="list-heading">Minhas Listas de Compras</h2>
    </div>



    <div class="row">
        <?php

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">' . $row['nome_lista'] . '</h5>
                    <a href="' . INCLUDE_PATH . 'ver_lista?id=' . $row['id'] . '" class="btn btn-primary">Ver Lista <i class="bi bi-list"></i></a>
                    <a href="' . INCLUDE_PATH . 'editar_lista?id=' . $row['id'] . '" class="btn btn-secondary">Editar <i class="bi bi-pencil-square"></i></a>
                    <a href="' . INCLUDE_PATH . 'excluir_lista?id=' . $row['id'] . '" class="btn btn-danger">Excluir <i class="bi bi-trash"></i></a>
                </div>
            </div>
          </div>';
            }
        } else {
            echo "Nenhuma lista de compras encontrada.";
        }

        // Fechar a conexão
        $conexao->close();
        ?>
    </div>

</div>