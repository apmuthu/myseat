<?php
 		
	// the URI we want to execute from the API
	// This is our development server :-)
 	$service_url = 'http://localhost/XT/06-code/api/1/guest_info/json.php?name=Bernd%20Orttenburger&token=f2b88cd6c9fd79ad6b0f1c43a5cb88edc169da2effe8';

	// create the curl handle
	$ch = curl_init();
	  
	// setting several options like url, timeout, returntransfer
	// the url
	curl_setopt($ch, CURLOPT_URL, $service_url);
	// the timeout
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	// returntransfer, put result in a variable
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// get the content of the url and put it into the output variable (thanks to the returntransfer option)
	$result = curl_exec($ch);

	// decode JSON result to PHP array
	$output = json_decode($result);


	// Print the result array
	echo '<pre>';
	print_r ($output);
	echo '</pre>';

	// example of storing the API result status code in variable
	$status = 	echo $output->status->code;


	// close the curl handle to free system resources
	curl_close($ch);

?>
