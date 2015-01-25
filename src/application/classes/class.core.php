<?php

/*
 * The MIT License
 *
 * Copyright 2015 HNK.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Core {

    private $users = array();

    public static function getCurrentUser() {
        if (isset($_SESSION["userid"])) {
            return getUser($_SESSION["userid"]);
        } else {
            return array("state" => "noUser");
        }
    }

    public static function getUser($userId) {
        if (array_key_exists($userId, $this->users)) {
            return array("state" => "ok", "user" => $this->users[$userId]);
        } else {
            Core::getClass("user");
            $user = new User($userId);
            $this->users[$userId] = $user;
            return array("state" => "ok", "user" => $user);
        }
    }

    public static function getClass($class) {
        require_once "application/classes/class.".$class.".php";
    }

    public static function makeDBConn() {
        Core::getClass("dbManager");
        Core::getClass("mysql");
        $sql = new MySQL();
        if (!$sql->state) exit;
        DBManager::build($sql);
    }

}
