<?php

namespace SamLex\SWCProspect\Page;

abstract class Page
{
    public function startPage()
    {
        print '
        <!DOCTYPE html>
        <html>
        ';
    }

    public function startHead($title)
    {
        print "
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
            <link rel='stylesheet' href='//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css'>
            <script src='//code.jquery.com/jquery-2.1.3.min.js'></script>
            <script src='//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js'></script>
            <title>$title</title>
        ";
    }

    public function endHead()
    {
        print '
        </head>
        ';
    }

    public function startBody()
    {
        print '
        <body>
        ';
    }

    public function endBody()
    {
        print '
        </body>
        ';
    }

    public function endPage()
    {
        print '
        </html>
        ';
    }

    public function outputPage($title)
    {
        $this->startPage();
        $this->startHead($title);
        $this->endHead();
        $this->startBody();
        $this->endBody();
        $this->endPage();
    }
}
