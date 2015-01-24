<?php
if (!isset($_REQUEST["page"])) {
    $page = "setup";
} else {
    $page = $_REQUEST["page"];
}
$types = array("setup" => "show");

$type = $types[$page];
if ($type === "show") {
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Setup</title>
        <link rel="stylesheet" type="text/css" href="styles/setup.css" />
    </head>
    <body>
        <div id="mainElement">
    <?php
}
//Begin pages
if ($type === "page") {
    
}
//End pages
if ($type === "show") {
    ?>
        </div>
    </body>
</html>
    <?php
}
