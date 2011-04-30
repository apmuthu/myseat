
/* ATTENTION: Run the following commands in your phpmyAdmin SQL console */


/* Update the database from v3 to XT */
/* --------------------------------- */

ALTER TABLE  `outlets` ADD  `saison_year` INT( 4 ) NOT NULL AFTER  `saison_end`;
ALTER TABLE  `outlets` ADD  `passerby_max_pax` INT( 12 ) NOT NULL;
ALTER TABLE  `outlets` CHANGE  `avg_duration`  `avg_duration` VARCHAR( 5 ) NOT NULL;
ALTER TABLE  `maitre` ADD  `outlet_child_passer_max_pax` INT( 12 ) NOT NULL;

INSERT INTO `l16n` (`id`, `needle`, `en`, `de`, `fr`, `es`, `nl`, `dk`, `se`, `it`, `fi`, `no`, `pl`, `tr`) VALUES 
(NULL, '_already_user_1', 'The unsername', 'Den Benutzername', 'The unsername', 'The unsername', 'The unsername', 'The unsername', 'The unsername', 'The unsername', 'The unsername', 'The unsername', 'The unsername', 'The unsername'), 
(NULL, '_already_user_2', 'does already exist.', 'gibt es bereits.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.', 'does already exist.');


/* Update the database from XT 0.1730 to > XT 0.1731 */
/* ------------------------------------------------- */

ALTER TABLE `properties` ADD `logo_filename` VARCHAR( 255 ) NOT NULL ,
ADD `status` VARCHAR( 10 ) NOT NULL DEFAULT 'active';

ALTER TABLE `settings` ADD `contactform_color_scheme` VARCHAR( 12 ) NOT NULL DEFAULT 'grey',
ADD `contactform_background` VARCHAR( 7 ) NOT NULL DEFAULT 'E0ECDB';


/* Update the database from XT 0.1732 to > XT 0.174 */
/* ------------------------------------------------- */

ALTER TABLE `reservations` ADD `reservation_advertise` VARCHAR( 5 ) NOT NULL DEFAULT 'NO';
ALTER TABLE `plc_users` ADD `confirm_code` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `outlets` ADD `1_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `1_close_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `2_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `2_close_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `3_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `3_close_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `4_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `4_close_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `5_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `5_close_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `6_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `6_close_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `0_open_time` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `0_close_time` time DEFAULT NULL;

/* Update the database from XT 0.174 to > XT 0.1744 */
/* ------------------------------------------------- */
ALTER TABLE `outlets` ADD `1_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `1_close_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `2_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `2_close_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `3_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `3_close_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `4_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `4_close_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `5_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `5_close_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `6_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `6_close_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `0_open_break` time DEFAULT NULL;
ALTER TABLE `outlets` ADD `0_close_break` time DEFAULT NULL;
INSERT INTO `l16n` (`id`, `needle`, `en`, `de`, `fr`, `es`, `nl`, `dk`, `se`, `it`, `fi`, `no`, `pl`, `tr`) VALUES
(190, '_break', 'Break', 'Pause', 'Pause', 'Pausa', 'Pauze', 'Pause', 'Paus', 'Pausa', '', '', '', ''),
(191, '_specific', 'Specific', 'Spezielle', 'Précis', 'Específico', 'Specifieke', 'Specifikke', 'Specifik', 'Specifico', '', '', '', ''),
(192, '_dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard', 'Dashboard');

/* Update the database from XT 0.1744 to > XT 0.1745 */
/* ------------------------------------------------- */
INSERT INTO `l16n` (`id`, `needle`, `en`, `de`, `fr`, `es`, `nl`, `dk`, `se`, `it`, `fi`, `no`, `pl`, `tr`) VALUES 
(193, '_reservation_advertise', 'I would like to receive informations by email.', 'Ich m&ouml;chte in Zukunft Informationen per Email erhalten.', 'Je voudrais recevoir des informations par e-mail.', 'Me gustaría recibir información por correo electrónico.', 'Ik wil graag informatie ontvangen per e-mail.', 'Jeg vil gerne modtage information via e-mail.', 'Jag vill få information via e-post.', 'Desidero ricevere informazioni via email.', '', '', '', ''),
(194, '_DR_', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.', 'Dr.'),
(195, '_PROF_', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.', 'Prof.');

/* Update the database from XT 0.178 to > XT 0.1782 */
/* ------------------------------------------------- */
ALTER TABLE `reservations` ADD `reservation_referer` TEXT NOT NULL ;

/* Update the database from XT 0.1782 to > XT 0.179 */
/* ------------------------------------------------- */
ALTER TABLE `properties` ADD `website` VARCHAR( 200 ) NOT NULL;
