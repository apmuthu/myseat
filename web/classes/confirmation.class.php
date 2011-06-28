<?php	

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	// Additional headers
	$headers .= 'From: mySeat Team <info@openmyseat.com>' . "\r\n";
	//$headers .= 'Bcc: support@myseat.us \r\n';


	//get a random 8 character string
    $_SESSION['confHash'] = randomString();

    //confirmation hash to database
    $rows = querySQL('user_confirm_code');
	
	// Get property details
	$property = querySQL('property_info');

	// Subject of email
	$subject = "Welcome to mySeat";
	
	// prepate logo file
	$logo = ($property['logo_filename']=='') ? 'logo.png' : $property['logo_filename'];
	$logo = $global_basedir.'uploads/logo/'.$logo;
		
	// prepate welcome text of email
	//$text = _user_email_confirmation;
	
	// prepate confirmation text of email
	$text = _user_activation_email;
	$message = sprintf( $text , $_POST['username'], $global_basedir, $_SESSION['confHash'], $global_basedir, $_SESSION['confHash']);
	
		// ===============
		// Email template
		// ===============
		$html_text = '
			<html>
			<head>

				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<title> User Activation </title>



			</head><body style="font-size: 12px; margin: 0; padding: 0; line-height: 22px; font-family: Arial, sans-serif; color: #555555; width: 100%;" bgcolor="#545454">

			<!-- WRAPPER TABLE --> 
			<table cellspacing="0" style="font-size: 12px; line-height: 22px; font-family: Arial, sans-serif; color: #555555; table-layout: fixed;" width="100%" cellpadding="0"><tr><td style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" bgcolor="#545454"> 	
			<!-- ///////////////////////////////////// NEWSLETTER CONTENT  /////////////////////////////////// -->		

				<br>	

				<p align="center" style="font-size: 12px; margin: 0 0 12px; padding: 0; line-height: 22px; font-family: Arial, sans-serif; color: #999999; text-align: center;">Email not displaying correctly? <a href="#" style="text-decoration: underline; color: #999999;">View it in your browser.</a></p>

			<!-- ////////////////////////////////// START MAIN CONTENT WRAP ////////////////////////////////// -->	

				<table rules="none" cellspacing="0" border="0" frame="border" align="center" style="border-color: #E4E2E4 #E4E2E4 #E4E2E4 #E4E2E4; font-size: 12px; border-collapse: collapse; background-color: #ffffff; line-height: 22px; font-family: Arial, sans-serif; color: #555555; border-spacing: 0; border-style: solid solid solid solid; border-width: 10px 0px 0px 0px;" width="600" cellpadding="0" bgcolor="#ffffff">
				<tr><td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

			<!-- ////////////////////////////////// START HEADER ////////////////////////////////////////////  -->

					<br><br>

					<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
						<tr>
							<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="260">

	<div style="background-image: url('.$logo.'); display: block; background-position:center center; background-repeat: no-repeat; width: 250px; height: 80px; display: block;"></div>

							</td>
							<td style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="20">&nbsp;</td><!-- spacer -->
							<td align="right" valign="top" style="font-size: 12px; line-height: 16px; font-family: Arial, sans-serif; color: #555555;" width="260">

								'._website.'
								<br><b style="font-weight: bold;"><a href="'.$property['website'].'" style="color: #3279BB; text-decoration: underline;">'.$property['website'].'</a></b>

							</td>
						</tr>
					</table>

					<img src="'.$global_basedir.'web/images/email/divider-600x61.gif" border="0" height="61" alt="" style="border: none; display: block;" width="600" />

			<!-- ////////////////////////////////// END HEADER /////////////////////////////////////////////// -->

				</td></tr>	
				<tr><td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

			<!-- ////////////////////////////////// START MAIN CONTENT. ADD MODULES BELOW //////////////////// -->

					<!-- Module #1 | 1 col, 540px -->
					<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
						<tr>

							<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

								<br/>'.$message.'<br/><br/>

							</td>

						</tr>	
					</table>
					<!-- End Module #1 -->

			<!-- ////////////////////////////////// END MAIN CONTENT ///////////////////////////////////////// -->

				</td></tr>
				<tr><td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

			<!-- ////////////////////////////////// START FOOTER ///////////////////////////////////////////// -->

					<img src="'.$global_basedir.'web/images/email/divider-600x31-2.gif" border="0" height="31" alt="" style="border: none; display: block;" width="600" />

					<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="600" cellpadding="0" bgcolor="#f0f0f0">
						<tr>
							<td valign="top" style="font-size: 12px; border-top-color: #D4D6D4; line-height: 22px; font-family: Arial, sans-serif; color: #555555; border-top-style: solid; border-top-width: 1px;">

								<img src="'.$global_basedir.'web/images/email/footer-divider-600x16.gif" border="0" height="16" alt="" style="border: none; display: block;" width="600" />

								<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
									<tr>
										<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="206">

											<h4 style="padding: 0; font-size: 12px; margin: 0 0 14px; line-height: 18px; color: #252525; font-weight: bold;"><span style="color: #252525;">'._description.'</span></h4>

											'.substr($_SESSION['selOutlet']['outlet_description'],0,90).'...
											<a href="#" style="color: #3279BB; text-decoration: underline;">Read More</a>

										</td>
										<td style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="20">&nbsp;</td><!-- spacer -->
										<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="146">

											<h4 style="padding: 0; font-size: 12px; margin: 0 0 14px; line-height: 18px; color: #252525; font-weight: bold;"><span style="color: #252525;">Email Options</span></h4>

											<a href="'.$global_basedir.'contactform/cancel.php?p=2" style="color: #3279BB; text-decoration: underline;">'._cancel.' '._reservations.'</a>
											<br><a href="http://www.myseat.us/terms.php" style="color: #3279BB; text-decoration: underline;">Terms & Conditions</a>
											<br><a href="http://www.myseat.us/privacy.php" style="color: #3279BB; text-decoration: underline;">Privacy Policy</a>

										</td>
										<td style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="20">&nbsp;</td><!-- spacer -->
										<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="146">

											<h4 style="padding: 0; font-size: 12px; margin: 0 0 14px; line-height: 18px; color: #252525; font-weight: bold;"><span style="color: #252525;">'._online.'</span></h4>

											<a href="http://www.facebook.com/bernd.orttenburger" style="color: #3279BB; text-decoration: underline;"><img src="'.$global_basedir.'web/images/email/social/facebook.png" border="0" height="28" alt="" style="border: none;" width="28" /></a>
											<a href="http://twitter.com/#!/bistro79" style="color: #3279BB; text-decoration: underline;"><img src="'.$global_basedir.'web/images/email/social/twitter.png" border="0" height="28" alt="" style="border: none;" width="28" /></a>
											<a href="http://de.linkedin.com/in/orttenburger" style="color: #3279BB; text-decoration: underline;"><img src="'.$global_basedir.'web/images/email/social/linkedin.png" border="0" height="28" alt="" style="border: none;" width="28" /></a>

										</td>
									</tr>
								</table>

								<img src="'.$global_basedir.'web/images/email/footer-divider-600x31.gif" border="0" height="31" alt="" style="border: none; display: block;" width="600" />

								<!-- company info + subscription -->
								<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
									<tr>
										<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

											   <b style="font-weight: bold;">© '.$property['name'].'</b>, '.$property['street'].', '.$property['city'].', '.$property['zip'].'<br/> T: '.$property['phone'].' |
												E: <a href="mailto:'.$property['email'].'" style="color: #3279BB; text-decoration: underline;">'.$property['email'].'</a>

										</td>
									</tr>	
								</table>
								<!-- end company info + subscription -->

								<img src="'.$global_basedir.'web/images/email/footer-divider-600x31-2.gif" border="0" height="31" alt="" style="border: none; display: block;" width="600" />

							</td>
						</tr>
					</table>

			<!-- ////////////////////////////////// END FOOTER /////////////////////////////////////////////// -->

				</td></tr>
				</table>

			<!-- ////////////////////////////////// END MAIN CONTENT WRAP //////////////////////////////////// -->

				<br><br><br>

			<!-- ///////////////////////////////////// END NEWSLETTER CONTENT  /////////////////////////////// -->			
			</td></tr></table><!-- END WRAPPER TABLE -->

			</body>
			</html>';


	//***
	//SEND OUT MAIL
		mail( $_POST['email'], $subject, $html_text, $headers); 
	//***
	
?>