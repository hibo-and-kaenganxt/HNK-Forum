function navigate(target) {
    var type = target.type;
    var name = target.name;
    $.ajax({
        url: "get.php",
        data: "type=" + type + "&id=" + name,
        success: function(answer) {
            $("body").append(answer);
        }
    });
}