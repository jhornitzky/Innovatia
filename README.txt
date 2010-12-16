These directories contain the code for the early version of the innoworks project.

By default, all requests are routed through index.php, which then routes to the appropriate UI (login or innoworks) under the ui/ folder.

The login contains very simple html and php functions primiarily aroudn display of info and registration.authentication.

The admin has admin functionality that can only be accessed by an admin, which includes DB, Announcements, LDAP, Users and more.

The innoworks folder cotains a number of php files that are modelled on a modular MVC design, with ajax controllers for each of the different views.
In some cases these are not fully MVC.

All DB/model layer functions reside as services within the core/ directory. 

Other files have more comenting on the project and howeach and everything works
For more info on Innoworks and open innovation head to http://innovatia.org to get a better perspective on how innovation works!