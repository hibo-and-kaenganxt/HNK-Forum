<!DOCTYPE html>
<html>
    <head>
        <title>HNK Administration</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="styles/acp.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen:400,300&subset=latin,latin-ext" type="text/css" />
    </head>
    <body>
        <div id="mainElement">
            <h2>HNK Administration</h2>
            <form id="loginForm" action="#" method="POST">
                <input type="text" id="username" name="username" required placeholder="Username"/><br><br>
                <input type="password" id="password" name="password" required placeholder="Password"/><br><br>
                <input type="submit" id="submit" value="Login"/>
            </form>
        </div>
        <script type="text/javascript" src="https://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha3.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript">
            $('#loginForm').submit(function (e) {
                e.preventDefault();
                if ($('#username').val() !== "") {
                    $('#username').css("border-color", "red");
                } else if ($('#password').val() !== "") {
                    $('#password').css("border-color", "red");
                } else {
                    $('#submit').val("Please wait...").attr("disabled", "disabled");
                    var data = "username=" + $('#username').val();
                    data += "&password=" + CryptoJS.SHA3($('#password').val(), {outputLength: 256});
                    $.ajax({
                        url: "contents/acp/loginProcess.php",
                        type: "POST",
                        data: data,
                        error: function () {
                            $('#submit').val("Login").prop("disabled", false);
                            // TODO
                        },
                        dataType: "json",
                        success: function (answer) {
                            // TODO
                        }
                    });
                }
            });
        </script>
    </body>
</html>
