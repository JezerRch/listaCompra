<?php
// Verificar se foi fornecido um ID de lista válido na URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obter o ID da lista da URL
    $lista_id = $_GET['id'];

    // Excluir a lista de compras e seus itens relacionados
    $sql = "DELETE FROM listas WHERE id = $lista_id";
    if ($conexao->query($sql) === TRUE) {
        echo "Lista de compras excluida com sucesso!";
        header("Location: dashboard"); // Redireciona para o dashboard.php
        exit();
    } else {
        echo "Erro ao excluir a lista de compras: " . $conexao->error;
    }

    // Fechar a conexão
    $conexao->close();
} else {
    echo "ID de lista inválido.";
}
