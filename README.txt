=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
=-=                                       =-=
=-=           mySeat README               =-=
=-=                                       =-=
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
=-= Version: 0.2160                        =-= 
=-= Date:    08.12.2012                   =-= 
=-= Time:    16:00 GMT                    =-= 
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


mySeat - Restaurant Reservation software.

Beautifully simple restaurant reservations.
Collaborate effortlessly on reservations.
mySeat will help you keep track of your reservations with ease.


News
====

 * New Repo - http://github.com/apmuthu/myseat
 * Get the latest tarball at: https://nodeload.github.com/apmuthu/myseat/tar.gz/master
 * Add Property Vulnerability Workaround - rename and disable web/properties.php when not needed

 
CHANGELOG
=========

2012-12-08 == mySeat v0.2160 == Ap.Muthu - http://github.com/apmuthu/myseat

 * Multi Property Enabled - when plc_user role = 1
 * Forum Fixes and features incorporated
 * Code cleanup
 * $settings['mailCharset'] in config.general.php
 * Typos corrected
 * PHP Notices fixed - missing variable checks done
 * TimeZone values updated
 * Asia/Singapore TimeZone incorporated
 * tooltip over day off in online reservation datepicker
 * property_grid zip field display
 * export_page, hooks fixed

2012-08-06 == mySeat v0.2150 == Sebastien Fanals - http://github.com/fanals/myseat

 * DB Table Prefixes enables multiple mySeat installs in one DB
 * mail fixed
 * local_mail plugin - GMail and HotMail SMTP enabled and tested working
 * $settings['emailSMTP'] in config.general.php

2012-01-18 == mySeat v0.2134 == Bernd Orttenburger - http://github.com/myseat/myseat
- Dutch language file improvements
- small bugfixes

There is a database update necessary for 0.195 version and up!


INSTALLATION
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
SEE ONLINE DOCUMENTATION FOR MORE DETAILS: http://www.myseat.us/API 
mySeat is easy to install.
Under most circumstances installing mySeat is a very simple process
and takes less than ten minutes to complete.

Before starting the automatic installer follow these instructions:
Create a database for mySeat on your web server.
Create a MySQL user who has all privileges for accessing and modifying it.
Open file WEBROOT/config/config.general.php in a text editor and fill in your database details.
Browse to your new mySeat site to the directory http://IP.OR.DOMAIN/PATH/install
This will take you to the mySeat automatic installer with small explanations.


UPDATE
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
To update mySeat to a newer version, it is not necessary to do a full install.
Just replace the old files on the web server, except the WEBROOT/config folder.
If there is a need to change or extend the database, it is clearly stated.
To update the database, point your web browser to mySeat update script at
http://IP.OR.DOMAIN/PATH/install/update.php


MULTILINGUAL
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
mySeat is actually translated into 9 languages:
English
German
Spanish
French
Dutch
Swedish
Italian
Chinese
Danish


GNU LICENSE
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
Copyright
mySeat is free software: you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or any later version.
mySeat is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with mySeat? 
If not, see <http://www.gnu.org/licenses/>.


mySeat? 
If not, see <http://www.gnu.org/licenses/>.


