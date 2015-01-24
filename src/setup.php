<?php
session_start();
if (!isset($_REQUEST["page"])) {
    if (!isset($_SESSION["setup"])) {
        $page = "setup";
    } else {
        $page = $_SESSION["setup"];
    }
} else {
    $page = $_REQUEST["page"];
}
$types = array("setup" => "show", "db" => "show", "404" => "show", "save_db" => "compute");

if (!array_key_exists($page, $types)) {
    $page = "404";
} else {
    $_SESSION["setup"] = $page;
}
$type = $types[$page];
if ($type === "show") {
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Setup</title>
        <link rel="stylesheet" type="text/css" href="styles/setup.css" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oxygen:400,300&subset=latin,latin-ext" type="text/css" />
    </head>
    <body>
        <div id="mainElement">
    <?php
}
//Begin pages
if ($page === "setup") {
    ?>
        <h2>Setup</h2>
        <div>Welcome to the ..name here.. setup!<br /> On the following pages you can setup your project page!</div>
        <a href="setup.php?page=db" class="continueButton">Continue</a>
    <?php
} else if ($page === "db") {
    ?>
        <h2>Database setup</h2>
        <form action="setup.php?page=save_db" method="POST">
            
        </form>
    <?php
} else if ($page === "404") {
    ?>
        <h2>404 - Setup page not found</h2>
        <a href="setup.php">Back to last setup page</a>
    <?php
}
//End pages
if ($type === "show") {
    ?>
        </div>
    </body>
</html>
    <?php
}
