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

class DBManager {

    private static $DBObj;
    private static $prefix;

    public static function build(MySQL $DB) {
        DBManager::$DBObj = $DB;
        DBManager::$prefix = $DB->getPrefix();
    }

    public static function getPageData($pageId) {
        $page = DBManager::$DBObj->query("SELECT name, hidden, perm FROM ".DBManager::$prefix."home_pages WHERE id = '".DBManager::escape($pageId)."'");
        if ($page === false || empty($page)) {
            return array("state" => "notfound");
        }
        $data = array();
        $data["state"] = "ok";
        $data["name"] = $page[0]["name"];
        $data["hidden"] = $page[0]["hidden"];
        $data["needPerm"] = ($page[0]["perm"] == null) ? false : true;
        $data["perm"] = $page[0]["perm"];
        $widgets = DBManager::$DBObj->query("SELECT ".DBManager::$prefix."home_widgets.id as id, pos, pos_id, ".DBManager::$prefix."home_widgets.width, "
                                     . DBManager::$prefix."home_widgets.height, title, "
                                     . DBManager::$prefix."widget_types.width as defWidth, ".DBManager::$prefix."widget_types.height as defHeight, lang_key,"
                                     . " type FROM ".DBManager::$prefix."home_widgets LEFT JOIN ".DBManager::$prefix."widget_types"
                                     . " ON type = ".DBManager::$prefix."widget_types.id WHERE page = '".DBManager::escape($pageId)."'"
                                     . " ORDER BY pos_id");
        $data["widgets"] = $widgets;
        return $data;
    }

    private static function escape($str) {
        return DBManager::$DBObj->escapeString($str);
    }

    public static function getAllUserPerms($userId) {
        $perms = DBManager::$DBObj->query("SELECT perm FROM ".DBManager::$prefix."users_perms WHERE userId = '".DBManager::escape($userId)."'");
        $return = array();
        foreach($perms as $perm) {
            array_push($return, $perm["perm"]);
        }
        $groups = DBManager::$DBObj->query("SELECT groupId FROM ".DBManager::$prefix."user_groups WHERE userId = '".DBManager::escape($userId)."'");
        foreach ($groups as $group) {
            foreach (getGroupPerms($group['groupId']) as $perm) {
                array_push($return, $perm);
            }
        }
        return $return;
    }

    public static function getGroupPerms($groupId) {
        $hasAll = DBManager::$DBObj->query("SELECT hasAll, parent FROM ".DBManager::$prefix."groups WHERE id = '".DBManager::escape($groupId)."'");
        if (empty($hasAll)) return;
        $perms = array();
        if ($hasAll[0]["hasAll"] == 1) {
            array_push($perms, "all_perms");
        }
        $gPerms = DBManager::$DBObj->query("SELECT perm FROM ".DBManager::$prefix."groups_perms WHERE userId = '".DBManager::escape($groupId)."'");
        foreach ($gPerms as $perm) {
            array_push($perms, $perm["perm"]);
        }
        if ($hasAll[0]["parent"] != null) {
            foreach (getGroupPerms($hasAll[0]["parent"]) as $perm) {
                array_push($perms, $perm);
            }
        }
        return $perms;
    }

    public static function getUserdata($userId) {

    }
}