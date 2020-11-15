<?php
    include_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <title>Objednávka | Úhel pohledu</title>
    </head>
    <body>
        <?php
            if(isset($order) && $order instanceof Order)
            {
                if ($order->printOrder()) {
                    include_once __DIR__ . '/views/orderView.php';
                } else if($order->isCompleted()) {
                    include_once __DIR__ . '/views/orderSuccessView.php';
                }else {
                    include_once __DIR__ . '/views/orderErrorView.php';
                }
            }
        ?>
        <script src="scripts/script.js"></script>
    </body>
</html>