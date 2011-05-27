=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
=-=                                       =-=
=-=           mySeat README               =-=
=-=                                       =-=
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
=-= Version: 0.1798                       =-= 
=-= Date:    22.05.2011                   =-= 
=-= Time:    14:12 GMT                    =-= 
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


mySeat - Anti-frustration reservation software.

Beautifully simple restaurant reservations.
Collaborate effortlessly on reservations.
Whether you are a restaurant team of 5 or a Grand-Hotel,
mySeat will help you keep track of your reservations with ease.

LAST CHANGES in 0.1797 & 0.1798
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
- Dansk language added
- Small bugfixes in contactform
- Security update on web/classes/autocomplete.php

DATABASE ADD in 0.1798
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
See documentation/database_update.sql:
In table properties two fields for social media added

DATABASE ADD in 0.1795
In table outlets one field added

INSTALLATION
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
mySeat is well known for its ease of installation.
Under most circumstances installing mySeat is a very simple process
and takes less than five minutes to complete.

Before starting the automatic installer follow these instructions:
Create a database for mySeat on your web server, as well as a MySQL user who has all privileges for accessing and modifying it.
Open <config/config.general.php> and <PLC/plc.class.php> in a text editor and fill in your database details.
Browse to your new mySeat site to the directory /install.
This will take you to the mySeat installer, which starts with a small explanation.


MAIN FEATURES
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
VALIDATION
mySeat validates the reservation data to be shure doing reservations right.
mySeat also corrects automatically the entered values or only lets you input data which makes sense.
INTELLIGENCE
mySeat remembers on old, critical and doublicate reservations. Reduce your risk of No-Shows!
You will also get a hint while reserving large tables to choose a common menu.
TABLE MANAGEMENT
mySeat has an easy to use table management to assign table number, status and seating.
The table assignment still sits at the restaurant manager.
TIME CONTROL
mySeat shows instanlty the occupany by time in a well-arranged bargraph.
You see clearly the next timeslot to book, to prevent all guest coming at the same time.
MULTILINGUAL
mySeat is designed to be multilingual. 
STATISTIC
The most significant key data is displayed as statistics to give you an overview over your outlets.
HISTORY
Every time you edit a reservation detail, mySeat remembers the authors name and the date.
mySeat summarizes and show you a history about the guests preferences.
INFORMATION
Publish info messages and special events. Gain maximum flexibility with in- and decreasing the capacity for every single day.


MULTILINGUAL
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
Actual mySeat is translated into

'en_EN' => 'English'
'de_DE' => 'German'
'es_ES' => 'Spanish'
'fr_FR' => 'French'
'nl_NL' => 'Dutch'
'se_SE' => 'Swedish'
'it_IT' => 'Italian'
'cn_CN' => 'Chinese'


LICENSE
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
Copyright
mySeat is free software: you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or any later version.
mySeat is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with mySeat.?If not, see <http://www.gnu.org/licenses/>.
