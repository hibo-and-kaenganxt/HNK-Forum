$(document).ready(function() {
    $('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oxygen:400,300&subset=latin,latin-ext" type="text/css" />');
    $.getScript("scripts/main/navigate.js", function() {
        $.ajax({
            url: "contents/main/main.html",
            success: function(answer) {
                $("body").append(answer);
                $("#loader").hide();
                navigate({"type": "page", "name": "1"});
            }
        });
    });
});