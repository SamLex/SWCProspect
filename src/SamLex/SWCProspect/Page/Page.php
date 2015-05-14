<?php

namespace SamLex\SWCProspect\Page;

abstract class Page
{
    public function startPage()
    {
        printf('
        <!DOCTYPE html>
        <html>
        ');
    }

    public function startHead($title)
    {
        printf("
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
            <link rel='stylesheet' href='//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css'>
            <script src='//code.jquery.com/jquery-2.1.3.min.js'></script>
            <script src='//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js'></script>
            <style>
                .ui-title
                {
                    white-space: normal !important;
                }
            </style>
            <title>%s</title>
        ", $title);
    }

    public function endHead()
    {
        printf('
        </head>
        ');
    }

    public function startBody()
    {
        printf('
        <body>
        ');
    }

    public function endBody()
    {
        printf('
        </body>
        ');
    }

    public function endPage()
    {
        printf('
        </html>
        ');
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
