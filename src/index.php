<?php
    include_once "config.php";
    if (isset($noSetup) && $noSetup) {
        header("Location: setup.php");
        exit;
    }
    if (!isset($config)) {
        file_put_contents("config.php", '<?php $noSetup = true;');
        header("Location: setup.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Please wait...</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="scripts/main/loader.js"></script>
        <link rel="stylesheet" type="text/css" href="styles/main.css" />
    </head>
    <body>
        <div id="loader">
            <img src="images/init-loader.gif" width="90" />
            <span>Loading...</span>
        </div>
        <noscript>
        <!-- TODO: Please enable js msg -->
        </noscript>
    </body>
</html>