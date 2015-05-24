<?php

/** Part of SWCProspect, contains ErrorPage class. */
namespace SamLex\SWCProspect\Page;

/**
 * The error page.
 *
 * Shows a generic error page.
 */
class ErrorPage extends Page
{
    /**
     * Constructs a new ErrorPage instance.
     */
    public function __construct()
    {
        $this->setJQPageID('swcprospect-error-page');
        $this->setTitle('Error!');
        $this->addToJQContent('<p><b>An error occured, probably with the database. Please try again later.</b></p>');
    }
}
