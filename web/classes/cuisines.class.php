<?php

// Once used, please DO NOT CHANGE anyway!
$cuisines = array(
	'Afghan',
	'African',
	'American (New)',
	'American (Traditional)',
	'Argentinean',
	'Asian',
	'Australian',
	'Austrian',
	'Bagels',
	'Bakeries',
	'Bar Food',
	'Barbecue',
	'Bavarian',
	'Belgian',
	'Bistro',
	'Brazilian',
	'British (Modern)',
	'British (Traditional)',
	'Burgers',
	'Burmese',
	'Cajun & Creole',
	'Californian',
	'Caribbean',
	'Central American',
	'Central Asian',
	'Cheesesteaks',
	'Chicken',
	'Chinese',
	'Coffeehouses',
	'Cuban',
	'Delis',
	'Desserts',
	'Dim Sum',
	'Diners & Coffee Shops',
	'Dutch',
	'Eastern European',
	'International',
	'Ethiopian',
	'Filipino',
	'Fish & Chips',
	'French',
	'Gastropub',
	'German',
	'Greek',
	'Haitian',
	'Hawaiian',
	'Health Food',
	'Hot Dogs',
	'Indian',
	'Indonesian',
	'International',
	'Irish',
	'Italian',
	'Jamaican',
	'Japanese',
	'Korean',
	'Kosher',
	'Latin American',
	'Local/Organic',
	'Malaysian',
	'Mediterranean',
	'Mexican',
	'Middle Eastern',
	'Moroccan',
	'New England',
	'Noodle Shops',
	'Nuevo Latino',
	'Pakistani',
	'Pan-Asian',
	'Persian',
	'Peruvian',
	'Pizza',
	'Polish',
	'Portuguese',
	'Russian',
	'Salads',
	'Sandwiches',
	'Scandinavian',
	'Seafood',
	'Small Plates/Tapas',
	'Smoothies/Juice Bar',
	'Soups',
	'South American',
	'Southern & Soul',
	'Southwestern',
	'Spanish',
	'Sri Lankan',
	'Steakhouses',
	'Sushi',
	'Swiss',
	'Teahouses',
	'Thai',
	'Tibetan',
	'Turkish',
	'Vegan',
	'Vegetarian',
	'Venezuelan',
	'Vietnamese',
	'Wild Game',
	'Wine Bar',
	'Wings',
	'Other'
	);

function cuisineDropdown($cuisines,$selected=1){

	// Set standard
	if ($selected==0 || $selected=='') {
		$selected=50;
	}
	
	echo "<select name='cuisine_style' id='cuisine_style' size='1'>\n";
	// loooping...
	foreach ($cuisines as $key => $value) {
		echo "<option value='".($key+1)."' ";
		echo ($selected==$key+1) ? "selected='selected'" : "";
		echo ">".$value."</option>\n";
	}		
	
	echo "</select>\n";
}
?>