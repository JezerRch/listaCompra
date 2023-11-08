<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $nome, $senha_hash);
            if ($stmt->fetch() && password_verify($senha, $senha_hash)) {
                $_SESSION["id"] = $id;
                $_SESSION["nome"] = $nome;
                header("Location: dashboard"); // Redireciona para a página do painel após o login
                exit();
            } else {
                echo "Credenciais inválidas.";
            }
        } else {
            echo "Credenciais inválidas.";
        }
    } else {
        echo "Erro ao autenticar: " . $stmt->error;
    }

    $conexao->close();
}
?>

<div class="container">
    <h1>Login</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" name="senha" required>
        </div>
        <div class="mt-5 row">
            <div class="col-6">
                <button type="submit" class="btn btn-primary col-12">Entrar</button>
            </div>
            <div class="col-6">
                <a class="btn btn-primary col-12" href="<?php echo INCLUDE_PATH; ?>register">Cadastrar</a>
            </div>
        </div>
    </form>
</div>


<script>
    $(document).ready(function() {
        $("#openModal").click(function() {
            $("#myModal").css("display", "block");
        });

        $(".close").click(function() {
            $("#myModal").css("display", "none");
        });
    });
</script>