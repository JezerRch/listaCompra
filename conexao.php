<?php
// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "lista_compras");
// $conexao = new mysqli("localhost", "id21338855_lista_compras", "Naruto123@", "id21338855_lista_compras");

// Verificar a conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Configurar o conjunto de caracteres para UTF-8 (opcional)
$conexao->set_charset("utf8");
