<?php
session_start();
include_once "config.php";
if (!isset($noSetup)) {
    header("Location: index.php");
    exit;
}
if (!isset($_REQUEST["page"])) {
    if (!isset($_SESSION["setup"])) {
        $page = "setup";
    } else {
        $page = $_SESSION["setup"];
    }
} else {
    $page = $_REQUEST["page"];
}
$types = array("setup" => "show", "db" => "show", "404" => "show", "save_db" => "compute", "try_db" => "show", "do_try_db" => "compute",
               "settings" => "show", "save_settings" => "compute", "admin" => "show", "save_admin" => "compute", "final" => "show",
               "do_final" => "show", "do_final_ajax" => "compute");
$langs = array("de" => "Deutsch");

if (!array_key_exists($page, $types)) {
    $page = "404";
}
$type = $types[$page];
if ($type === "show" && $page !== "404") {
    $_SESSION["setup"] = $page;
}
if ($type === "show") {
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Setup</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles/setup.css" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oxygen:400,300&subset=latin,latin-ext" type="text/css" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    </head>
    <body>
        <div id="mainElement">
    <?php
}
//Begin pages
if ($page === "setup") {
    ?>
        <h2>Setup</h2>
        <div>Welcome to the HNK setup!<br /> On the following pages you can setup your project page!</div>
        <input type="button" onclick="window.location.href='setup.php?page=db'" class="continueButton" value="Continue" />
        <noscript>
        <span style="color:red">
            You have to enable JavaScript to setup your page!
        </span>
        </noscript>
    <?php
} else if ($page === "db") {
    if (isset($_SESSION["mysql_setup"]) && $_SESSION["mysql_setup"] === "true") {
        $host = $_SESSION["mysql_host"];
        $port = $_SESSION["mysql_port"];
        $user = $_SESSION["mysql_user"];
        $pass = $_SESSION["mysql_pass"];
        $db = $_SESSION["mysql_db"];
        $prefix = $_SESSION["mysql_prefix"];
    } else {
        $host = "localhost";
        $port = "3306";
        $user = "";
        $pass = "";
        $db = "hnk";
        $prefix = "HNK_";
    }
    ?>
        <h2>Database setup</h2>
        <form action="setup.php?page=save_db" method="POST" id="mysql_config">
            <label for="mysql_host_input">Host: </label>
            <input name="mysql_host" id="mysql_host_input" type="text" placeholder="MySQL Host" value="<?php echo $host; ?>" required />
            <span class="inputInfo mysqlInput" id="mysql_host"></span>
            <br /><br />
            <label for="mysql_port_input">Port: </label>
            <input name="mysql_port" id="mysql_port_input" type="text" placeholder="MySQL Port" value="<?php echo $port; ?>" required />
            <span class="inputInfo mysqlInput" id="mysql_port"></span>
            <br /><br />
            <label for="mysql_user_input">Username: </label>
            <input name="mysql_user" id="mysql_user_input" type="text" placeholder="MySQL Username" required value="<?php echo $user; ?>" />
            <span class="inputInfo mysqlInput" id="mysql_user"></span>
            <br /><br />
            <label for="mysql_pass_input">Password: </label>
            <input name="mysql_pass" id="mysql_pass_input" type="password" placeholder="MySQL Password" required value="<?php echo $pass; ?>" />
            <span class="inputInfo mysqlInput" id="mysql_pass"></span>
            <br /><br />
            <label for="mysql_db_input">Database: </label>
            <input name="mysql_db" id="mysql_db_input" type="text" placeholder="MySQL Database" required value="<?php echo $db; ?>" />
            <span class="inputInfo mysqlInput" id="mysql_db"></span>
            <br /><br />
            <label for="mysql_prefix_input">Prefix: </label>
            <input name="mysql_prefix" id="mysql_prefix_input" type="text" placeholder="MySQL Prefix" value="<?php echo $prefix; ?>" />
            <span class="inputInfo mysqlInput" id="mysql_prefix"></span>
            <br /><br />
            <input type="submit" value="Continue" class="continueButton" />
        </form>
        <div id="mysql_info">
            <h3>Info</h3>
            Be sure to create a user <span id="mysql_info_user" style='display:none'>called <output></output></span> with the following permissions on the database<output id="mysql_info_db"></output>:<br />
            SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX<br />
        </div>
        <script type="text/javascript">
            function is_int(value) {
                if((parseFloat(value) === parseInt(value)) && !isNaN(value)){
                    return true;
                } else {
                    return false;
                }
            }
            $("#mysql_user_input, #mysql_db_input").bind("keyup change", function() {
                if ($("#mysql_user_input").val() === "") {
                    $("#mysql_info_user").hide();
                } else {
                    $("#mysql_info_user").show().children("output").html($("#mysql_user_input").val());
                }
                $("#mysql_info_db").html($("#mysql_db_input").val() === "" ? "" : " " +  $("#mysql_db_input").val());
            });
            $("#mysql_config").submit(function(e) {
                $(".inputInfo").html("").css("display", "none");
                $("#mysql_config input").css("border-color", "#ccc");
                var prevent = false;
                if ($("#mysql_host_input").val() === "") {
                    prevent = true;
                    $("#mysql_host_input").css("border-color", "red");
                    $("#mysql_host").html("Please fill out this field!").show();
                }
                var port_val = $("#mysql_port_input").val();
                if (port_val === "" || !is_int(port_val)) {
                    prevent = true;
                    $("#mysql_port_input").css("border-color", "red");
                    $("#mysql_port").html("This field has to contain a number!").show();
                } else if (port_val < 1 || port_val > 65535) {
                    prevent = true;
                    $("#mysql_port_input").css("border-color", "red");
                    $("#mysql_port").html("The port has to be between 1 and 65535!").show();
                }
                if ($("#mysql_user_input").val() === "") {
                    prevent = true;
                    $("#mysql_user_input").css("border-color", "red");
                    $("#mysql_user").html("Please fill out this field!").show();
                }
                if ($("#mysql_pass_input").val() === "") {
                    prevent = true;
                    $("#mysql_pass_input").css("border-color", "red");
                    $("#mysql_pass").html("Please fill out this field!").show();
                }
                if ($("#mysql_db_input").val() === "") {
                    prevent = true;
                    $("#mysql_db_input").css("border-color", "red");
                    $("#mysql_db").html("Please fill out this field!").show();
                }
                if (prevent) {
                    e.preventDefault();
                }
            });
        </script>
    <?php
} else if ($page === "404") {
    header("HTTP/1.1 404 Not Found");
    ?>
        <h2>404 - Setup page not found</h2>
        <a href="setup.php">Back to last setup page</a>
    <?php
} else if ($page === "save_db") {
    if (!isset($_POST["mysql_host"]) || !isset($_POST["mysql_port"]) || !isset($_POST["mysql_user"]) || !isset($_POST["mysql_pass"]) || !isset($_POST["mysql_db"]) || !isset($_POST["mysql_prefix"])) {
        echo "Error";
        exit;
    }
    $host = trim($_POST["mysql_host"]);
    $port = trim($_POST["mysql_port"]);
    $user = trim($_POST["mysql_user"]);
    $pass = trim($_POST["mysql_pass"]);
    $db = trim($_POST["mysql_db"]);
    $pre = trim($_POST["mysql_prefix"]);
    if ($host === "" || $port === "" || $user === "" || $pass === "" || $db === "" || !is_numeric($port) || $port < 1 || $port > 65535) {
        echo "Error";
        exit;
    }
    $_SESSION["mysql_host"] = $host;
    $_SESSION["mysql_port"] = $port;
    $_SESSION["mysql_user"] = $user;
    $_SESSION["mysql_pass"] = $pass;
    $_SESSION["mysql_db"] = $db;
    $_SESSION["mysql_prefix"] = $pre;
    $_SESSION["mysql_setup"] = "true";
    header("Location: setup.php?page=try_db");
} else if ($page === "try_db") {
    if (!isset($_SESSION["mysql_setup"]) || $_SESSION["mysql_setup"] !== "true") {
        echo "<script type='text/javascript'>window.location.href='setup.php?page=db';</script>";
        exit;
    }
    ?>
        <h2>Database connection test</h2>
        <div id="db_check_container"></div>
        <script type="text/javascript">
            function tryIt() {
                $("#db_check_container").html("Please wait...");
                $.ajax({
                    url: "setup.php?page=do_try_db",
                    success: function(answer) {
                        $("#db_check_container").html(answer).append("&nbsp;&nbsp;&nbsp;<input type='button' onclick='tryIt()' value='Try again' />");
                    }
                });
            }
            tryIt();
        </script>
    <?php
} else if ($page === "do_try_db") {
    if (!isset($_SESSION["mysql_setup"]) || $_SESSION["mysql_setup"] !== "true") {
        echo "<input type='button' onclick=\"window.location.href='setup.php?page=db'\" value='Setup database first!' />";
        exit;
    }
    $mysql = @new \mysqli($_SESSION["mysql_host"], $_SESSION["mysql_user"], $_SESSION["mysql_pass"], $_SESSION["mysql_db"], $_SESSION["mysql_port"]);
    if ($mysql->connect_errno) {
        echo "Could not connect: ".$mysql->connect_error."<br /><br />";
        echo "<input type='button' onclick=\"window.location.href='setup.php?page=db'\" value='Back to setup' />";
        exit;
    }
    $grants = $mysql->query("SELECT * FROM `information_schema`.`SCHEMA_PRIVILEGES`");
    $globalGrants = $mysql->query("SELECT * FROM `information_schema`.`USER_PRIVILEGES`");
    if ($grants == false || $globalGrants == false) {
        echo "Could not lookup permissions. Are you sure you set up the mysql permissions correctly?<br /><br />";
        echo "<input type='button' onclick=\"window.location.href='setup.php?page=db'\" value='Back to setup' />";
        exit;
    }
    if ($grants->num_rows == 0 && $globalGrants->num_rows == 0) {
        echo "No permissions found. Are you sure you set up the mysql permissions correctly?<br /><br />";
        echo "<input type='button' onclick=\"window.location.href='setup.php?page=db'\" value='Back to setup' />";
        exit;
    }
    $array = array();
    while ($line = $grants->fetch_array(MYSQLI_ASSOC)) {
        array_push($array, $line);
    }
    while ($line = $globalGrants->fetch_array(MYSQLI_ASSOC)) {
        array_push($array, $line);
    }
    $perms = array("SELECT" => false, "INSERT" => false, "UPDATE" => false, "DELETE" => false, "CREATE" => false, "ALTER" => false, "INDEX" => false);
    foreach ($array as $perm) {
        if (!isset($perm["TABLE_SCHEMA"]) || str_replace("\\", "", $perm["TABLE_SCHEMA"]) === $_SESSION["mysql_db"]) {
            if (array_key_exists($perm["PRIVILEGE_TYPE"], $perms)) {
                $perms[$perm["PRIVILEGE_TYPE"]] = true;
            }
        }
    }
    $missing = false;
    foreach ($perms as $perm => $has) {
        if (!$has) {
            $missing = true;
            echo "Missing permission ".$perm."!<br /><br />";
        }
    }
    if ($missing) {
        echo "<input type='button' onclick=\"window.location.href='setup.php?page=db'\" value='Back to setup' />";
        exit;
    } else {
        $_SESSION["mysql_working"] = "true";
        if (!isset($_SESSION["from_final"])) {
            echo "<script type='text/javascript'>window.location.href='setup.php?page=settings';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='setup.php?page=final';</script>";
        }
    }
} else if ($page === "settings") {
    if (!isset($_SESSION["mysql_working"]) || $_SESSION["mysql_working"] !== "true") {
        echo "<script type='text/javascript'>window.location.href='setup.php?page=try_db';</script>";
        exit;
    }
    $name = isset($_SESSION["setup_name"]) ? $_SESSION["setup_name"] : "";
    ?>
        <h2 style="display:inline;">Settings - <input type="button" onclick="window.location.href='setup.php?page=db'" value="Back" /></h2>
        <div id="db_success">MySQL connection successfully tested!</div><br /><br />
        <form action="setup.php?page=save_settings" method="POST" id="settingsForm">
            <input type="text" placeholder="Display name" id="config_name" required name="config_name" value="<?php echo $name; ?>" /><span class="inputInfo" id="config_name_info"></span><br /><br />
            <h3>Languages: </h3>
            <input type="checkbox" disabled checked />Englisch<br />
            <?php
            $selLangs = isset($_SESSION["setup_langs"]) ? json_decode($_SESSION["setup_langs"], true) : array();
            foreach($langs as $key => $value) {
                $checked = array_key_exists($key, $selLangs) ? "checked " : "";
                echo "<input type='checkbox' name='config_lang_".$key."' class='config_lang' data-key='".$key."' id='config_lang_".$key."' ".$checked."/><label for='config_lang_".$key."'>".$value."</label>";
            }
            ?>
            <br /><br />
            <input type="submit" class="continueButton" value="Continue" />
        </form>
        <script type="text/javascript">
            $("#settingsForm").submit(function(e) {
                $(".inputInfo").css("display", "none");
                $("#config_name").css("border-color", "#ccc");
                var name = $("#config_name").val();
                if (name === "") {
                    $("#config_name_info").show().html("Please fill out this field!");
                    $("#config_name").css("border-color", "red");
                    e.preventDefault();
                    return;
                }
            });
        </script>
    <?php
} else if ($page === "save_settings") {
    if (!isset($_SESSION["mysql_working"])) {
        header("Location: setup.php?page=try_db");
        exit;
    }
    if (!isset($_POST["config_name"])) {
        echo "Error";
        exit;
    }
    $name = trim($_POST["config_name"]);
    if ($name === "") {
        echo "Error";
        exit;
    }
    $selLangs = array();
    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 12) == "config_lang_") {
            $lang = substr($key, 12, 15);
            $selLangs[$lang] = true;
        }
    }
    $_SESSION["setup_name"] = $name;
    $_SESSION["setup_langs"] = json_encode($selLangs);
    if (!isset($_SESSION["from_final"])) {
        header("Location: setup.php?page=admin");
    } else {
        header("Location: setup.php?page=final");
    }
} else if ($page === "admin") {
    if (!isset($_SESSION["setup_name"])) {
        echo "<script type='text/javascript'>window.location.href='settings';</script>";
        exit;
    }
    if (isset($_SESSION["setup_email"])) {
        $email = $_SESSION["setup_email"];
        $user = $_SESSION["setup_user"];
    } else {
        $email = "";
        $user = "";
    }
    ?>
        <h2>Admin account - <input type="button" onclick="window.location.href='setup.php?page=settings'" value="Back" /></h2>
        <script type="text/javascript" src="https://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha3.js"></script>
        <form action="setup.php?page=save_admin" method="POST" id="adminForm">
            <label for="admin_email">Email: </label>
            <input type="email" id="admin_email" name="admin_email" placeholder="Email address" required value="<?php echo $email; ?>" /><span class="inputInfo adminInput" id="admin_email_info"></span>

            <br /><br />
            <label for="admin_name">Username: </label>
            <input type="text" id="admin_name" name="admin_name" placeholder="Admin username" required value="<?php echo $user; ?>" /><span class="inputInfo adminInput" id="admin_user_info"></span>

            <br /><br />
            <label for="admin_pass">Password: </label>
            <input type="password" id="admin_pass" placeholder="Password" required value="" /><span class="inputInfo adminInput" id="admin_pass_info"></span>

            <br /><br />
            <label for="admin_pass2">Repeat password: </label>
            <input type="password" id="admin_pass2" placeholder="Password again" required value="" /><span class="inputInfo adminInput" id="admin_pass2_info"></span>
            <input type="hidden" name="admin_pass" id="admin_pass_input" />
            <br /><br />
            <input type="submit" class="continueButton" value="Continue" />
        </form>
        <script type="text/javascript">
            $("#adminForm").submit(function(e) {
                var email = $("#admin_email").val();
                var user = $("#admin_user").val();
                var pass = $("#admin_pass").val();
                var pass2 = $("#admin_pass2").val();
                var prevent = false;
                $(".adminInput").css("display", "none");
                $("#adminForm input").css("border-color", "#ccc");
                if (email === "") {
                    prevent = true;
                    $("#admin_email_info").show().html("Please fill out this field!");
                    $("#admin_email").css("border-color", "red");
                }
                if (user === "") {
                    prevent = true;
                    $("#admin_user_info").show().html("Please fill out this field!");
                    $("#admin_user").css("border-color", "red");
                }
                if (pass === "") {
                    prevent = true;
                    $("#admin_pass_info").show().html("Please fill out this field!");
                    $("#admin_pass").css("border-color", "red");
                } else if (pass2 === "") {
                    prevent = true;
                    $("#admin_pass2_info").show().html("Please fill out this field!");
                    $("#admin_pass2").css("border-color", "red");
                } else if (pass !== pass2) {
                    prevent = true;
                    $("#admin_pass2_info").show().html("The passwords do not match!");
                    $("#admin_pass2").css("border-color", "red");
                }
                if (prevent) {
                    e.preventDefault();
                    return;
                }
                crypto.subtle.digest("SHA-256", new TextEncoder("utf-8").encode(pass)).then(function (hash) {
                    return hex(hash);
                });
                var passEnc = CryptoJS.SHA3(pass, { outputLength: 256 });
                $("#admin_pass_input").val(passEnc);
            });
        </script>
    <?php
} else if ($page === "save_admin") {
    if (!isset($_SESSION["setup_name"])) {
        header("Location: setup.php?page=settings");
        exit;
    }
    if (!isset($_POST["admin_email"]) || !isset($_POST["admin_name"]) || !isset($_POST["admin_pass"])) {
        echo "Error";
        exit;
    }
    $email = trim($_POST["admin_email"]);
    $user = trim($_POST["admin_name"]);
    $pass = trim($_POST["admin_pass"]);
    if (strlen($pass) != 64) {
        echo "Error";
        exit;
    }
    $_SESSION["setup_email"] = $email;
    $_SESSION["setup_user"] = $user;
    $_SESSION["setup_pass"] = $pass;
    header("Location: setup.php?page=final");
} else if ($page === "final") {
    if (!isset($_SESSION["setup_email"])) {
        echo "<script type='text/javascript'>window.location.href='admin';</script>";
        exit;
    }
    $_SESSION["from_final"] = true;
    ?>
        <h2>The final step!</h2>
        <div class="finalBlock">
            <h3>Database setup:</h3>
            Host: <?php echo $_SESSION["mysql_host"].":".$_SESSION["mysql_port"]; ?><br />
            Username: <?php echo $_SESSION["mysql_user"]; ?><br />
            Password: ***<br />
            Database: <?php echo $_SESSION["mysql_db"]; ?><br /><br />
            <input type="button" value="Edit" onclick="window.location.href='setup.php?page=db'" />
        </div>
        <div class="finalBlock">
            <h3>Settings:</h3>
            Page name: <?php echo $_SESSION["setup_name"]; ?><br />
            Languages: Englisch<?php foreach(json_decode($_SESSION["setup_langs"]) as $lang => $l) { echo ", ".$langs[$lang]; } ?><br /><br />
            <input type="button" value="Edit" onclick="window.location.href='setup.php?page=settings'" />
        </div>
        <div class="finalBlock">
            <h3>Admin user:</h3>
            Email: <?php echo $_SESSION["setup_email"]; ?><br />
            Username: <?php echo $_SESSION["setup_user"]; ?><br />
            Password: ***<br /><br />
            <input type="button" value="Edit" onclick="window.location.href='setup.php?page=admin'" />
        </div>
        <br /><br /><input type="button" onclick="window.location.href='setup.php?page=do_final'" class="continueButton" value="Setup!" />
    <?php
} else if ($page === "do_final") {
    if (!isset($_SESSION["from_final"])) {
        header("Location: setup.php?page=final");
    } else if (!isset($_SESSION["setup_email"]) || !isset($_SESSION["setup_user"]) || !isset($_SESSION["setup_pass"])) {
        header("Location: setup.php?page=admin");
    } else if (!isset($_SESSION["setup_name"]) || !isset($_SESSION["setup_langs"])) {
        header("Location: setup.php?page=settings");
    } else if (!isset($_SESSION["mysql_working"])) {
        header("Location: setup.php?page=try_db");
    } else {
        $_SESSION['from_final'] = 'verified';
        ?>
        <h2>Setting up <?php echo $_SESSION['setup_name']; ?>...</h2>
        <div>
            Please wait!<br />
            A config file is being created, the database is being set up and your user is being created...
        </div>
        <script>
            $.ajax({
                url: "setup.php?page=do_final_ajax",
                error: function() { alert("An error occured!"); },
                success: function(answer) {
                    if (answer === "done") {
                        $("#mainElement>h2").html("Done!");
                        $("#mainElement>div").html("You will be forwarded to your new page after 3 seconds!");
                        setTimeout(function() {window.location.href="index.php";}, 3000);
                    } else {
                        $("#mainElement").append(answer);
                    }
                }
            });
        </script>
        <?php
    }
} else if ($page === "do_final_ajax") {
    if (!isset($_SESSION["from_final"]) || $_SESSION["from_final"] !== "verified") {
        header("Location: setup.php?page=do_final");
        exit;
    }
    if (file_exists("config.php")) {
        if (!rename("config.php", "config.old.php")) {
            unlink("config.php");
        }
    }
    $DB1 = new mysqli($_SESSION["mysql_host"], $_SESSION["mysql_user"], $_SESSION["mysql_pass"], $_SESSION["mysql_db"], $_SESSION["mysql_port"]);
    $_SESSION["mysql_prefix"] = $DB1->escape_string(str_replace(array("/", "\\", ".", ":", "*", "?", "\"", "<", ">", "|"), "", $_SESSION["mysql_prefix"]));
    $DB1->close();
    $content = '<?php
    /*
     * Config file of the HNK instance
     * Most of the settings can be changed through the acp
     * You should only touch the database settings if you have to
    */

    $config["DB"]["host"] = "'.$_SESSION["mysql_host"].'";
    $config["DB"]["port"] = '.$_SESSION["mysql_port"].';
    $config["DB"]["user"] = "'.$_SESSION["mysql_user"].'";
    $config["DB"]["pass"] = "'.$_SESSION["mysql_pass"].'";
    $config["DB"]["db"] = "'.$_SESSION["mysql_db"].'";
    $config["DB"]["prefix"] = "'.$_SESSION["mysql_prefix"].'";


    $config["name"] = "'.$_SESSION["setup_name"].'";
    $config["langs"] = \''.$_SESSION["setup_langs"].'\';
    ';

    file_put_contents("config.php", $content);
    require "config.php";
    require_once "application/classes/class.mysql.php";
    $DB = new MySQL();
    $prefix = $config["DB"]["prefix"];
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."users (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'User Id', username TEXT NOT NULL COMMENT 'User chosen username', "
             . "password VARCHAR(64) NOT NULL COMMENT 'SHA3 hashed user password', mail TEXT NOT NULL COMMENT 'User email address') CHARACTER SET 'utf8', ENGINE 'InnoDB', "
             . "COMMENT 'Main user table'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."users_profile (userId INT UNSIGNED NOT NULL PRIMARY KEY COMMENT 'User Id', "
             . "firstname TEXT NULL COMMENT 'First name', lastname TEXT NULL COMMENT 'Last name', FOREIGN KEY (userId) REFERENCES ".$prefix."users(id) ON DELETE CASCADE)"
             . " CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'User profile page'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."groups (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Group Id', name TEXT NOT NULL COMMENT 'User chosen group name', "
             . "parent INT UNSIGNED NULL COMMENT 'Parent group id', hasAll BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Has all perms', INDEX parent_group (parent),"
             . " FOREIGN KEY (parent) REFERENCES ".$prefix."groups(id) ON DELETE SET NULL) CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Groups'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."users_groups (userId INT UNSIGNED NOT NULL COMMENT 'User Id',"
             . "groupId INT UNSIGNED NOT NULL COMMENT 'Group Id', UNIQUE INDEX usergroup (userId, groupId), FOREIGN KEY (userId) REFERENCES ".$prefix."users(id) ON DELETE CASCADE,"
             . " FOREIGN KEY (groupId) REFERENCES ".$prefix."groups(id) ON DELETE CASCADE) CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Usergroups'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."users_perms (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique Id', userId INT UNSIGNED NOT NULL COMMENT 'User Id', "
             . "perm TEXT NOT NULL COMMENT 'Permission', FOREIGN KEY (userId) REFERENCES ".$prefix."users(id) ON DELETE CASCADE) "
             . "CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'User permissions'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."groups_perms (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique Id', groupId INT UNSIGNED NOT NULL COMMENT 'Group Id', "
             . "perm TEXT NOT NULL COMMENT 'Permission', FOREIGN KEY (groupId) REFERENCES ".$prefix."groups(id) ON DELETE CASCADE) "
             . "CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Group permissions'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."home_pages (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Page Id', name TEXT NOT NULL COMMENT 'Page name', "
             . "hidden BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Is this page hidden', perm TEXT NULL COMMENT 'May need permission')"
             . " CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Homepage pages'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."widget_types (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Widget Id', "
             . "width INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Default width', height INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Default height', "
             . "lang_key TEXT NOT NULL COMMENT 'Translatable lang name') CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Existing widget types'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."home_widgets (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique id', page INT UNSIGNED NOT NULL COMMENT 'Widget page', "
             . "type INT UNSIGNED NOT NULL COMMENT 'Widget type', pos ENUM('left', 'right', 'top', 'bottom') NOT NULL COMMENT 'Widget position', "
             . "pos_id INT UNSIGNED NOT NULL COMMENT 'Widget position on given side', width INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Widget width', "
             . "height INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Widget height', title TEXT NULL COMMENT 'Widget title', FOREIGN KEY (page) REFERENCES ".$prefix."home_pages (id), FOREIGN KEY (type) REFERENCES ".$prefix."widget_types (id)) "
             . "CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Widget definitions'");
    echo $DB->lastSQLError();
    $DB->query("CREATE TABLE IF NOT EXISTS ".$prefix."widget_text (id INT UNSIGNED NOT NULL PRIMARY KEY COMMENT 'Widget id', text TEXT NULL COMMENT 'Text content', "
             . "FOREIGN KEY (id) REFERENCES ".$prefix."home_widgets (id)) CHARACTER SET 'utf8', ENGINE 'InnoDB', COMMENT 'Text widget contents'");
    echo $DB->lastSQLError();
    //Create user
    $DB->query("INSERT INTO ".$prefix."users (username, password, mail) VALUES('".$DB->escapeString($_SESSION["setup_user"])."', '".$DB->escapeString($_SESSION["setup_pass"])."', "
             . "'".$DB->escapeString($_SESSION["setup_email"])."')");
    echo $DB->lastSQLError();
    $userId = $DB->getInsertId();
    $DB->query("INSERT INTO ".$prefix."users_profile (userId) VALUES(".$userId.")");
    echo $DB->lastSQLError();
    $DB->query("INSERT INTO ".$prefix."groups (name, hasAll) VALUES('Administrator', 1)");
    echo $DB->lastSQLError();
    $groupId = $DB->getInsertId();
    $DB->query("INSERT INTO ".$prefix."users_groups VALUES (".$userId.", ".$groupId.")");
    echo $DB->lastSQLError();
    $DB->query("INSERT INTO ".$prefix."home_pages (name) VALUES ('Home')");
    echo $DB->lastSQLError();
    $homepageId = $DB->getInsertId();
    require_once "install/widgets.php";
    $sql = "INSERT INTO ".$prefix."widget_types (width, height, lang_key) VALUES ";
    $first = true;
    foreach ($widgets as $widget) {
        if (!$first) {
            $sql .= ", ";
        } else {
            $first = false;
        }
        $sql .= "(".$widget['width'].", ".$widget['height'].", '".$widget['name']."')";
    }
    $DB->query($sql);
    echo $DB->lastSQLError();
    $DB->query("INSERT INTO ".$prefix."home_widgets (page, type, pos, pos_id, width, height, title) VALUES ('".$homepageId."', (SELECT id FROM ".$prefix."widget_types WHERE lang_key = 'blog'), "
             . "'left', 0, 3, -1, 'Blog')");
    echo $DB->lastSQLError();
    $DB->query("INSERT INTO ".$prefix."home_widgets (page, type, pos, pos_id, width, height, title) VALUES ('".$homepageId."', (SELECT id FROM ".$prefix."widget_types WHERE lang_key = 'text'), "
             . "'right', 0, 1, 1, '".$DB->escapeString($_SESSION['setup_name'])."')");
    echo $DB->lastSQLError();
    $widgetId = $DB->getInsertId();
    $DB->query("INSERT INTO ".$prefix."widget_text VALUES('".$widgetId."', 'Welcome to your newly setup HNK system. <br />Use the acp to configure it properly.')");
    echo $DB->lastSQLError();
    session_unset();
    echo "done";
}
//End pages
if ($type === "show") {
    ?>
        </div>
    </body>
</html>
    <?php
}
