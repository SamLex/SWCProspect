<?php

namespace SamLex\SWCProspect\Page;

/*
    The base page from which all other pages extend

    Overriders are recommended to call the various setter methods from there constructors

    Due the jQuery Mobile's page loading mechanism, any CSS and JS must be included in every page so that, no matter the entry point, everything looks and acts properly.
    As such any CSS and JS is recommended to be put in the page.{js/css} files
*/
abstract class Page
{
    // Variables to get replaced into the template
    private $title = '';
    private $head = '';
    private $jqPageID = '';
    private $jqHeaderBeforeTitle = '';
    private $jqHeaderAfterTitle = '';
    private $jqContent = '';
    private $jqAfterContent = '';
    private $body = '';

    // The base page template
    private $pageTemplate = "
    <!DOCTYPE html>
    <html>
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
            <link rel='stylesheet' href='//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css'>
            <link rel='stylesheet' href='res/css/page.css'>
            <script src='//code.jquery.com/jquery-2.1.3.min.js'></script>
            <script src='//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js'></script>
            <script src='res/js/page.js'></script>
            <title>%%TITLE%%</title>
            %%MORE_HEAD%%
        </head>
        <body>
            <div data-role='page' data-theme='b' id='%%JQUERY_PAGE_ID%%'>
                <div data-role='header' data-position='inline'>
                    %%JQUERY_HEADER_BEFORE_TITLE%%
                    <h1>%%TITLE%%</h1>
                    %%JQUERY_HEADER_AFTER_TITLE%%
                </div>
                <div data-role='content'>
                    %%JQUERY_CONTENT%%
                </div>
                %%JQUERY_AFTER_CONTENT%%
            </div>
            %%MORE_BODY%%
        </body>
    </html>
    ";

    // Set the page title, used in the HTML title and header
    public function setTitle($title)
    {
        $this->title = $title;
    }

    // Set the JQuery Mobile page id. Each page MUST have a unique ID
    public function setJQPageID($id)
    {
        $this->jqPageID = $id;
    }

    // Add elmements to the pages head
    public function addToHead($addition)
    {
        $this->head = $this->head.$addition;
    }

    // Add elmements to the pages header before the header title
    // Mainly to add left buttons
    public function addToJQHeaderBeforeTitle($addition)
    {
        $this->jqHeaderBeforeTitle = $this->jqHeaderBeforeTitle.$addition;
    }

    // Add elmements to the pages header after the header title
    // Mainly to add right buttons
    public function addToJQHeaderAfterTitle($addition)
    {
        $this->jqHeaderAfterTitle = $this->jqHeaderAfterTitle.$addition;
    }

    // Add elmements to the pages content
    public function addToJQContent($addition)
    {
        $this->jqContent = $this->jqContent.$addition;
    }

    // Add elmements after the pages content but still in the JQuery Mobile page
    // Mainly for adding popups
    public function addToJQAfterContent($addition)
    {
        $this->jqAfterContent = $this->jqAfterContent.$addition;
    }

    // Add elmements to the pages body after the JQuery Mobile page
    public function addToBody($addition)
    {
        $this->body = $this->body.$addition;
    }

    // Output the page by replacing all the placeholders in the template and echoing
    public function outputPage()
    {
        $page = $this->pageTemplate;

        $page = str_replace('%%TITLE%%', $this->title, $page);
        $page = str_replace('%%MORE_HEAD%%', $this->head, $page);
        $page = str_replace('%%JQUERY_PAGE_ID%%', $this->jqPageID, $page);
        $page = str_replace('%%JQUERY_HEADER_BEFORE_TITLE%%', $this->jqHeaderBeforeTitle, $page);
        $page = str_replace('%%JQUERY_HEADER_AFTER_TITLE%%', $this->jqHeaderAfterTitle, $page);
        $page = str_replace('%%JQUERY_CONTENT%%', $this->jqContent, $page);
        $page = str_replace('%%JQUERY_AFTER_CONTENT%%', $this->jqAfterContent, $page);
        $page = str_replace('%%MORE_BODY%%', $this->body, $page);

        echo $page;
    }
}
