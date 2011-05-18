<?php
	//LANGUAGE: NEDERLANDS
	
	// PLEASE USE HTML ENTITIES HERE !
	// e.g.: ö = &ouml;
	
	//----------------------------
	// HEADER
	//----------------------------
	
	// The text before the list of the available language -- "View this page in"
	$lang["change_language"] = 'Bekijk deze pagina in';
	
	// List the available languages sorted in an array, the keys are the slug names that correspond to each language file
	$lang["available_language"] = array(
		"en" => "English",
		"es" => "Espa&ntilde;ol",
		"de" => "Deutsch",
		"fr" => "Fran&ccedil;aise",
		"nl" => "Nederlandse",
		"cn" => "Chinese"
	);
	
	// The contact information in the top-right of the page -- "www.openmyseat.com <br /> www.myseat.us"
	$lang["contact_info"] = 'www.degrieksekeuken.nl<br />';



	//----------------------------
	// CONTENT
	//----------------------------
	
	// The page title -- "Select your prefered restaurant"
	$lang["title"] = '<strong>Online</strong> Reservering';
	
	// A line of text before the contact form -- "<h3>Get your table.Make an instant reservation now!</h3>"
	$lang["contact_form_intro"] = '<p>Boek nu uw tafel en reserveer nu direct!<br/>
	Voor Privé partijen, grote groepsboekingen of andere vragen kunt u ons bellen.</p>';
	
	// Default text of the contact form -- "Name or Company"
	$lang["contact_form_name"] = 'Naam of bedrijfsnaam';
	
	// Default text of the contact form -- "E-mail address"
	$lang["contact_form_email"] = 'E-mail adres';
	
	// Default text of the contact form -- "Security question"
	$lang["security_question"] = 'Beveiligingsvraag';
	
	// Default text of the contact form -- "Advertise"
	$lang["contact_form_advertise"] = 'Ik wil graag informatie ontvangen per e-mail.';
	
	// Default text of the contact form -- "Notes"	
	$lang["contact_form_notes"] = 'Opmerkingen of iets te vieren? Laat het ons weten!';
	
	// Default text of the contact form -- "Restaurant"
	$lang["contact_form_restaurant"] = 'Restaurant';
	
	// Default text of the contact form -- "Time"
	$lang["contact_form_time"] = 'Tijd';
	
	// Default text of the contact form -- "Phone"
	$lang["contact_form_phone"] = 'Telefoon';
	
	// Default text of the contact form -- "Number of persons"
	$lang["contact_form_pax"] = 'Aantal personen';
	
	// Default text of the contact form -- "Title"
	$lang["contact_form_title"] = 'Titel';

	// Default text for the 'captcha' field of the contact form -- "Are you human?"
	$lang["contact_form_captcha"] = 'Ter voorkoming spam, vul hiernaast het antwoord in';
	
	// The 'Send' button -- "Book"
	$lang["contact_form_send"] = 'Bevestig uw reservering';
	
	// The 'Back' button -- "Back"
	$lang["contact_form_back"] = 'Terug';
	
	// The 'Cancel' button -- "Cancel"
	$lang["contact_form_cxl"] = 'Annuleer reservering';
	
	// Message text -- "Booking number"
	$lang["book_num"] = 'Reserverings nummer';
	
	// The success message -- "Thank you for your reservation. An email confirmation was sent. Your Booking number is:"
	$lang["contact_form_success"] = 'Bedankt voor uw reservering bij Restaurant De Griekse Keuken. Er is naar het opgegeven emailadres een bevestiging gestuurd.<br/> Uw reserveringsnummer is: ';
	
	// The full outlet message -- "Some errors occurred while booking, please try again."
	$lang["contact_form_full"] = 'Helaas kan er voor deze datum niet worden gereserveerd, probeert a.u.b. een andere datum.';
	
	// The failure message -- "Some errors occurred while booking, please try again."
	$lang["contact_form_fail"] = 'Er is een fout opgetreden tijdens het boeken, probeert u het a.u.b. nogmaals.';
	
	// The success message -- "Thank you for your reservation. An email confirmation was sent. Your Booking number is:"
	$lang["cxl_form_success"] = 'Uw reservering is geannuleerd! Toch hopen wij u binnenkort bij ons te mogen begroeten.<br/>';
	
	//----------------------------
	// CONTACT FORM
	//----------------------------
	
	// The titles
	$lang["_M_"] = 'Hr.'; // Mr.
	$lang["_DR_"] = 'Dr.'; // Dr.
	$lang["_PROF_"] = 'Prof.'; // Prof.
	$lang["_W_"] = 'Mevr.'; // Mrs.
	$lang["_F_"] = 'Familie'; // Family
	$lang["_C_"] = 'Bedrijfsnaam'; // Company

	//----------------------------
	// BOOKING
	//----------------------------
	
	// The emails subject -- "Reservation confirmation for"
	$lang["email_subject"] = 'Reserverings bevestiging voor';
	
	// The page title -- "Reservation</strong> Process"
	$lang["conf_title"] = '<strong>Reserverings voortgang</strong>';
	
	// The page title -- "Storno</strong> Cancel your reservation"
	$lang["cxl_title"] = '<strong>Annuleer</strong> uw reservering';
	
	// A subline of text I -- "Reservation progress for"
	$lang["conf_intro"] = 'Reserverings voortgang voor';
	
	// A subline of text -- "Directly clear your reservation."
	$lang["cxl_intro"] = 'Direct uw reservering verwijderen.';
	
	// A subline of text II -- "at"
	$lang["_at_"] = 'te';
	
	// Day off error message
	$lang["error_dayoff"] = 'Sorry, het restaurant is vandaag gesloten.';

	//----------------------------
	// FOOTER
	//----------------------------
	
	// The content on the first footer column
	$lang["footer_one"] =  '<h3><a href="index.php">Plaats uw reservering</a></h3>
	<p></p>';
							
	// The content on the second footer column
	$lang["footer_two"] =  '<h3><a href="cancel.php">Annuleer uw reservering</a></h3>
	<p></p>';
							
	// The content on the third footer column
	$lang["footer_three"] = '<h3><a href="'.$_SERVER['DOCUMENT_ROOT'].'">Terug naar de beginpagina</a></h3>
	<p></p>';
	
	//----------------------------
	// MINI-FOOTER
	//----------------------------
	
	// The copyright text, do not change! -- "<a href="#">Copyright &copy; 2010 mySeat</a>"
	$lang["minifooter_copyright"] = '&copy; 2010 by <a href="http://www.openmyseat.com">MYSEAT</a> under the GPL license';
	
	
?>