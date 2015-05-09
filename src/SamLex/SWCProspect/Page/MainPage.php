<?php

namespace SamLex\SWCProspect\Page;

class MainPage extends Page
{
    public function startBody($title = 'Welcome to SWCProspect')
    {
        parent::startBody();

        print "
        <div data-role='page' data-theme='b' id='main-page'>
            <div data-role='header' data-position='inline'>
                    <h1>$title</h1>
            </div>
        ";
    }

    public function endBody()
    {
        print '
        </div>
        ';

        parent::endBody();
    }
}
