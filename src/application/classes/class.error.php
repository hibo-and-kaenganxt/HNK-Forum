<?php

class Error {

    public static function showError($type, $data) {
        echo $type;
        echo "<br/>\n";
        echo $data["code"];
        echo "<br/>\n";
        echo $data["msg"];
    }
}
