<?php

namespace SamLex\SWCProspect\Page;

/*
    The base page from which all other pages extend

    Overriders are recommended to call super of most/all methods

    Due the jQuery Mobile's page loading mechanism, any CSS and JS must be included in every page so that, no matter the entry point, everything looks and acts properly.
    As such any CSS and JS is recommended to be put in here
*/
abstract class Page
{
    /*
        Starts the page
    */
    public function startPage()
    {
        printf('
        <!DOCTYPE html>
        <html>
        ');
    }

    /*
        Starts the head
    */
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

                .main-page-planet-tile
                {
                    float:left;
                    max-width:15em;
                    width: 15em;
                    margin:1em;
                    text-align: center;
                }
            </style>
            <title>%s</title>
        ", $title);
    }

    /*
       Ends the head
    */
    public function endHead()
    {
        printf('
        </head>
        ');
    }

    /*
        Starts the body
    */
    public function startBody()
    {
        printf('
        <body>
        ');
    }

    /*
        Ends the body
    */
    public function endBody()
    {
        printf('
        </body>
        ');
    }

    /*
        Ends the page
    */
    public function endPage()
    {
        printf('
        </html>
        ');
    }

    /*
        Outputs the page in the recommended order
    */
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
