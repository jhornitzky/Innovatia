INNOVATIA README
v1.0 22/2/2011

These directories contain the code of the innovatia project.
For more info on Innoworks and open innovation head to http://innovatia.org to get a better perspective on how innovation works!

PARTS--------------------------------------------------------
By default, all requests are routed through index.php, which then routes to the appropriate UI (login or innoworks) under the ui/ folder.

LOGIN
The login contains very simple html and php functions primiarily around display of info and registration/authentication.

ADMIN
The admin has admin functionality that can only be accessed by an admin, which includes DB, Announcements, LDAP, Users and more. 
engine.php is the 'root' that iframes other pages.

INNOWORKS
The innoworks folder cotains a number of php files that are modelled on a modular MVC design, with ajax controllers for each of the different views.
In some cases these are not fully MVC.

Typical scenario:
1. The engine.php is the default display file, which includes all the JS. This is loaded when the user hits the index file.
2. All page transitions (with the exception of attachments) are handled throught innoworks.js via AJAX calls thru engine.ajax.php.
3. Engine.ajax.php includes neccessary *.ajax.php (e.g. compare.ajax.php). This concept is similar to a routes file.
4. the *.ajax.php file may responsd to action. This file is sort of like a controller.  
5. Commonly the *.ajax.php will call a *.ui.php script (e.g. compare.ui.php). These go and do some more stuff and then format a response.
6. The .ajax.php and/or ui.php file will then commly call a service. All DB/pseudo-model layer functions reside as services within the core/ directory. 

Files have more commenting on the project and how each and everything works.

A few small notes....
Currently there are no objects, results are simply fetched as objects via connector. There is an AutoObject that can wrap functions, however it is used sparingly and still cannot resolve same function names.

STRUCTURE--------------------------------------------------------
core/ - core stuff, like login, services and config. Should be simlinked in.
db/ - db scripts for DB setup. Should not be included in deployed code.
test/ - tests. Should be removed once ready for deploy.
ui/ - ui code and much of logic. Much of this should theoretically be outside web root.
users/ - user files. Shoudl be empty when deployed. hashed-value named images and the like. At some point should be moved outside webroot.
other files in webroot... all essential, should be part of webroot.

DEPLOYMENT-----------------------------------------------------------
1. Copy folders and files (/core, /ui, /users, index.php) across to new install location.
2. Modify (/core/innoworks.config.php) with correct variables (dbUrl, dbSchema, etc.).
3. Modify each thinConnector.php and pureConnector.php file with correct path to config directory. FIXME to write auto update script.
4. Create DB tables and data using latest sql script from DB folder.

TESTING------------------------------------
Currently all testing is done through the user interface...
Some basic tests of certain components lie in the test folder, but the majority of test cases must be executed manually.

IDEAS FOR CHANGES--------------------------
-Write some tests
-include more templates to separate ui/control from display


LICENSE
Copyright (C) 2011 James Hornitzky

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.