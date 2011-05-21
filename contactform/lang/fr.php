<?php 
	//LANGUAGE: ENGLISH 
 
	// PLEASE USE HTML ENTITIES HERE ! 
	// e.g.: š = &ouml; 
 
	//---------------------------- 
	// HEADER 
	//---------------------------- 
 
	// The text before the list of the available language -- "View this page in" 
    $lang["change_language"] = 'Voir cette page en';

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
	$lang["contact_info"] = 'www.openmyseat.com <br /> www.myseat.us'; 
  
	//---------------------------- 
	// CONTENT 
	//---------------------------- 
 
	// The page title -- "Select your preferred restaurant"
	$lang["title"] = 'Online Réservation'; 
 
	// A line of text before the contact form -- "<h3>Make an instant reservation now!</h3>"
	$lang["contact_form_intro"] = '<p>Effectuez une reservation instantanée 
	<br/>Pour des soirées privées, réservations de groupes ou toute autre demande, merci de bien vouloir nous appeler.</p>' ;  
	// Default text of the contact form -- "Name or Group Name" 
	$lang["contact_form_name"] = 'Nom ou Nom du Groupe'; 

 
	// Default text of the contact form -- "E-mail address"
	$lang["contact_form_email"] = 'Adresse email'; 
	 
	// Default text of the contact form -- "Security question"
	$lang["security_question"] = 'Question de sécurité'; 
	 
	// Default text of the contact form -- "Advertise"
	$lang["contact_form_advertise"] = 'J’aimerais recevoir des informations par email';
 
	// Default text of the contact form -- "Notes" 
	$lang["contact_form_notes"] = 'Commentaires de réservation';
	  
	// Default text of the contact form -- "Restaurant"
	$lang["contact_form_restaurant"] = 'Restaurant';
 
	// Default text of the contact form -- "Time" 
	$lang["contact_form_time"] = 'Heure'; 
 
	// Default text of the contact form -- "Phone"
	$lang["contact_form_phone"] = 'Téléphone'; 
 
	// Default text of the contact form -- "Number of persons"
	$lang["contact_form_pax"] = 'Nombre de personnes'; 
 
	// Default text of the contact form -- "Title"
	$lang["contact_form_title"] = 'Titre'; 
 
	// Default text for the 'captcha' field of the contact form -- "Security question" 
	$lang["contact_form_captcha"] = 'Question de sécurité';
 
	// The 'Send' button -- "Book"
	$lang["contact_form_send"] = 'Réserver'; 
 
	// The 'Back' button -- "Back" 
	$lang["contact_form_back"] = 'Retour'; 
 
	// The 'Cancel' button -- "Cancel"
	$lang["contact_form_cxl"] = 'Annuler Réservation'; 

 
	// Message text -- "Booking number"
	$lang["book_num"] = 'Numéro de réservation'; 
 
	// The success message -- "Thank you for your reservation. An email confirmation was sent. Your Booking number is:" 
	$lang["contact_form_success"] = 'Merci pour votre réservation. Un email de confirmation vous a été envoyé. <br/>  Votre numéro de réservation est'; 
 
	// The full outlet message -- "An error occurred while booking, please try again." 
	$lang["contact_form_full"] = 'Le restaurant ne peut pas être réservé à cette date, veuillez choisir une autre date s’il vous plaît.'; 
 
	// The failure message -- " An error occurred while booking, please try again "
	$lang["contact_form_fail"] = 'Une erreur s’est produite lors de la réservation, veuillez réessayer s’il vous plaît.'; 
 
	// The success message -- "Thank you for your reservation. An email confirmation was sent. Your Booking number is:"  
	$lang["cxl_form_success"] = 'Votre reservation a été annulée. A bientôt sur notre site!<br/>N’hésitez pas à nous contacter si vous avez des questions.'; 
 

	//---------------------------- 
	// CONTACT FORM 
	//---------------------------- 
 
	// The titles 
	$lang["_M_"] = 'Monsieur'; // Monsieur 
	$lang["_DR_"] = 'Docteur'; // Docteur.
	$lang["_PROF_"] = 'Professeur'; // Professeur. 
	$lang["_W_"] = 'Madame/Mademoiselle'; // Madame/Mademoiselle 
	$lang["_F_"] = 'Famille'; // Famille
	$lang["_C_"] = 'Groupe'; // Groupe 

 
	//---------------------------- 
	// BOOKING 
	//---------------------------- 
 
	// The emails subject -- "Reservation confirmation for" 	
	$lang["email_subject"] = 'Confirmation de réservation pour'; 
 
	// The page title -- "Reservation</strong> Process" 
	$lang["conf_title"] = '<strong>Réservation</strong> En cours';  //OU $lang["conf_title"] = '<strong>Processus de réservation'; 
 
	// The page title -- "Storno</strong> Cancel your reservation" 
	$lang["cxl_title"] = '<strong>Annulez</strong> votre réservation'; 
 
	// A subline of text I -- "Reservation progress for" 	
	$lang["conf_intro"] = 'Evolution de la réservation pour'; 
 
	// A subline of text -- "Annulez directement votre réservation." 
	$lang["cxl_intro"] = 'Pour annuler votre reservation, veuillez entrer votre numéro de reservation et l’adresse e-mail à laquelle la reservation a été envoyée.'; 
 
	// A subline of text II -- "at"
	$lang["_at_"] = 'à'; 
 
	// Day off error message 
	$lang["error_dayoff"] = 'Le restaurant ne peut pas être réservé ce jour-là.'; 
	
	//----------------------------
	// FOOTER
	//----------------------------

	// The content on the first footer 
	$lang["footer_one"] =  "<p><small>S'il ya d'autres questions ou demandes spéciales, s'il vous plaît n'hésitez pas à<br/>nous contacter au numéro de téléphone %s ou par courriel à <a href='mailto:%s'>%s</a>.</small></p>";

 
	//---------------------------- 
	// MINI-FOOTER 
	//---------------------------- 
 
	// The copyright text, do not change! -- "<a href="#">Copyright &copy; 2010 mySeat</a>" 
	$lang["minifooter_copyright"] = '&copy; 2010 by <a href="http://www.openmyseat.com">MYSEAT</a> under the GPL license'; 
 
 
?>
