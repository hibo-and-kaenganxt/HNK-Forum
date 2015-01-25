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

class Page {

    private $pageId;
    private $pageData;
    private $user;

    public function __construct($id) {
        Core::makeDBConn();
        $this->pageId = $id;
        $this->pageData = DBManager::getPageData($id);
        if ($this->pageData["state"] === "notfound" || $this->pageData["hidden"]) {
            $this->printNotFound();
            return;
        }
        $user = Core::getCurrentUser();
        if ($this->pageData["needPerm"] && $user["state"] === "noUser" ) {
            $this->printNoPerms();
            return;
        } else if ($this->pageData["needPerm"] && !$user["user"]->hasPerm($this->pageData["needPerm"])) {
            $this->printNoPerms();
            return;
        }
        $this->user = $user;
        $this->printPage();
    }

    private function printPage() {
        $pageTitle = $this->pageData["name"];
        echo "<div class='page' data-pageid='".$this->pageId."'>\n";
        echo "<div class='pageHead'><h2>".$pageTitle."</h2></div>\n";
        $this->printBar("top");
        $this->printBar("left");
        $this->printBar("right");
        $this->printBar("bottom");
        echo "</div>\n";
        echo "<script type='text/javascript'>$('title').html('".$pageTitle."');</script>";
    }

    private function printBar($where) {

    }

    private function printNotFound() {
        echo "Page not found!";
    }
}
