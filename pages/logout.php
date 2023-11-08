<?php
session_start();
session_destroy();
header("Location: index.php"); // Redireciona para a página de login após o logout
exit();
