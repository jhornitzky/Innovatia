These directories contain the code for the early version of the innoworks project.
For more info on Innoworks and open innovation head to http://innovatia.org to get a better perspective on how innovation works!

ARCHITECTURE--------------------------------------------------------
By default, all requests are routed through index.php, which then routes to the appropriate UI (login or innoworks) under the ui/ folder.

The login contains very simple html and php functions primiarily aroudn display of info and registration/authentication.

The admin has admin functionality that can only be accessed by an admin, which includes DB, Announcements, LDAP, Users and more. 
engine.php is the 'root' that iframes other pages.

The innoworks folder cotains a number of php files that are modelled on a modular MVC design, with ajax controllers for each of the different views.
In some rare cases these are not fully MVC.
The engine.php is the default display file, which includes all the JS. All page transitions (with the exception of attachments) 
are handled throught innoworks.js and AJAX calls thru engine.ajax.php.  

All DB/model layer functions reside as services within the core/ directory. 
Currently there are no objects, resutls are simply fetched as objects via connector.

Files have more commenting on the project and how each and everything works

DEPLOYMENT-----------------------------------------------------------
1. Copy folders and files (/core, /ui, /users, index.php) across to new install location.
2. Modify (/core/innoworks.config.php) with correct variables.
3. Modify each thinConnector.php and pureConnector.php file with correct path to config directory.