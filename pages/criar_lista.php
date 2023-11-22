<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: index"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit();
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obter o nome da lista do formulário
    $nome_lista = $_POST["nome_lista"];
    $usuario_id = $_SESSION["id"]; // Obtém o ID do usuário logado a partir da sessão

    // Inserir a nova lista de compras no banco de dados associada ao usuário
    $sql = "INSERT INTO listas (nome_lista, usuario_id) VALUES (?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $nome_lista, $usuario_id);

    if ($stmt->execute()) {
        echo "Lista de compras criada com sucesso!";
        header("Location: dashboard"); // Redireciona para o dashboard
        exit();
    } else {
        echo "Erro ao criar a lista: " . $conexao->error;
    }

    // Fechar a conexão
    $conexao->close();
}
