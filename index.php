<?php
session_start();

include('config.php');


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <script src="sw.js"></script>
</head>
</head>

<body>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function() {
                    console.log('service worker registered');
                })
                .catch(function() {
                    console.warn('service worker failed');
                });
        }
    </script>


    <?php
    $url = isset($_GET['url']) ? $_GET['url'] : 'login';
    if (file_exists('pages/' . $url . '.php')) {
        include('pages/' . $url . '.php');
    } else {
        //caso de erro ou a pagina não exista
        // include('pages/404.php');
        echo 'erro';
    }

    ?>


</body>

</html>