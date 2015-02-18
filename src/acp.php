<?php
require_once 'config.php';
if (isset($noSetup) && $noSetup) {
    header("Location: setup.php");
    exit();
}
if (!isset($_REQUEST['action']) || $_REQUEST['action'] === "") {
    $_REQUEST['action'] = "login";
}
$input = str_replace("/", "", $_REQUEST['action']);
if (file_exists('contents/acp/' . $input . '.php')) {
    require_once 'contents/acp/' . $input . '.php';
} else {
    header("HTTP/1.1 404 Not Found");
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Error 404 - Not Found</title>
            <meta charset="utf-8">
            <link rel="stylesheet" type="text/css" href="styles/acp.css" />
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen:400,300&subset=latin,latin-ext" type="text/css" />
        </head>
        <body>
            <div id="mainElement">
                <h2>Error 404 - Not Found</h2>
            </div>
        </body>
    </html>
    <?php
}
