<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROJETO BIBLIOTECA VIRTUAL - Luiz Pilotti</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h2>PROJETO BIBLIOTECA VIRTUAL - Luiz Pilotti</h2>
    </header>
    
    <div class="wrapper">
        <?php
            $url = isset($_GET['url']) ? $_GET['url'] : 'login';
            if(file_exists(INCLUDE_PATH.'paginas/'.$url.'.php')){
                include(INCLUDE_PATH.'paginas/'.$url.'.php');
            }
        ?>
    </div>
</body>
</html>