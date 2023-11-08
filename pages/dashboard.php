<?php
// session_start();

include("conexao.php");


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
        <div class="col-10">
            <h1>Bem-vindo, <?php echo $_SESSION["nome"]; ?>!</h1>
        </div>
        <div class="col-2">
            <a href="<?php echo INCLUDE_PATH; ?>logout" class="btn btn-danger">Sair</a>
        </div>
    </div>


    <h1>Minhas Listas de Compras</h1>

    <form class="mb-4" action="criar_lista.php" method="POST">
        <div class="form-group">
            <label for="nome_lista">Nome da Lista:</label>
            <input type="text" class="form-control" name="nome_lista" required>
        </div>
        <button type="submit" class="btn btn-primary">Criar Lista</button>
    </form>


    <div class="row">
        <?php

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">' . $row['nome_lista'] . '</h5>
                        <a href="' . INCLUDE_PATH . 'ver_lista?id=' . $row['id'] . '" class="btn btn-primary">Ver Lista</a>
                        <a href="' . INCLUDE_PATH . 'editar_lista?id=' . $row['id'] . '" class="btn btn-secondary">Editar Lista</a>
                        <a href="' . INCLUDE_PATH . 'excluir_lista?id=' . $row['id'] . '" class="btn btn-danger">Excluir Lista</a>
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