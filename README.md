# SWCProspect

A small website designed for use with the game SWCombine, for managing planetary prospecting results. Designed for submission to the "How to get noticed as a coder for SWC" [forum thread] (http://www.swcombine.com/forum/thread.php?thread=36174).

### Usage

This needs a web server, PHP and a MySQL server.

1. Clone the repo somewhere, preferable not web accessable.
2. Set a web root (using whatever web server you are using) to the web/ directory. Ensure PHP can escape this directory. 
3. Rename the conf/config-template.json file to config.json, and fill in the database credentials. 
4. Import the setup/swcprospect.sql file into your database.

The web site should now be available on you web server.

#### Note to actually usable

This is merely a prototype and includes NO user authentication. If you actually wished to use this I would recommend adding some form of user authentication to protect against malicious users.