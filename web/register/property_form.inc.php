<?php
if ($p == 2 || $_SESSION['page'] == 2){
	$link = '?p=7';
}elseif ($_SESSION['page'] == 6){
	$link = '?p=6&q=5';	
}else{
	$link = '?p=1';	
}

?>

<form method="post" action="<?php echo $link; ?>" id="property_form" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
	<label><?php echo _name;?></label>
	<p>
		<input type="text" name="name" id="name" class="required" minlength="4" title=' ' value="<?php echo $row['name'];?>"/>
	</p>
	<label><?php echo _contact;?></label>
	<p>
		<input type="text" name="contactperson" id="contactperson" class="required" minlength="4" title=' ' value="<?php echo $row['contactperson'];?>"/>
	</p>
	<label><?php echo _adress;?></label>
	<p>
		<input type="text" name="street" id="street" class="required" minlength="4" title=' ' value="<?php echo $row['street'];?>"/>
	</p>
	<label><?php echo _zip;?></label>
	<p>
		<input type="text" name="zip" id="zip" class="required" minlength="4" title=' ' value="<?php echo $row['zip'];?>"/>
	</p>
	<label><?php echo _city;?></label>
	<p>
		<input type="text" name="city" id="city" class="required" minlength="4" title=' ' value="<?php echo $row['city'];?>"/>
	</p>
	<label><?php echo _country;?></label>
	<p>
		<?php countryDropdown($countries,$row['country']); ?>
	</p>
	<label><?php echo _email;?></label>
	<p>
		<input type="text" name="email" id="email" class="required email" title=' ' value="<?php echo $row['email'];?>"/>
	</p>
	<label><?php echo _website;?></label>
	<p>
		<input type="text" name="website" id="website" class="required" title=' ' value="<?php echo $row['website'];?>"/>
	</p>
	<label><?php echo _phone;?></label>
	<p>		 	 	 	 	 	 	
		<input type="text" name="phone" id="phone" class="required" title=' ' value="<?php echo $row['phone'];?>"/>
	</p>
	<label><?php echo _fax;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="fax" id="fax" value="<?php echo $row['fax'];?>"/>
	</p>
	<label>Facebook Link</label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="social_fb" id="social_fb" value="<?php echo $row['social_fb'];?>"/>
	</p>
	<label>Twitter Link</label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="social_tw" id="social_tw" value="<?php echo $row['social_tw'];?>"/>
	</p>
	<label><?php echo _img;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="file" name="img[]" value=""/>
		<br/><small>best 350x250px | .gif .jpg .png</small>
	</p>
	<label>Logo</label>	
	<p>	 	 	 	 	 	 	
		<input type="file" name="img[]" value=""/>
		<br/><small>best 250x80px | .gif .jpg .png</small>
	</p>
			<input type="hidden" name="created" value="<?php echo date('Y-m-d H:i:s');?>">
			<input type="hidden" name="id" value="<?php echo ($row['id']) ? $row['id'] : 1;?>">
			<input type="hidden" name="new" value="<?php echo ($row['id']) ? 0 : 1;?>">
			<input type="hidden" name="propertyID" value="<?php echo $_SESSION['propertyID'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_prpty">
	<br/>
	<div style="text-align:center;">
		<input type="submit" class="button_dark" value="<?php echo _save;?>">
	</div>
</form>