<?
if ($p == 2 || $_SESSION['page'] == 2){
	$link = '?p=7';
}elseif ($_SESSION['page'] == 6){
	$link = '?p=6&q=5';	
}else{
	$link = '?p=1';	
}

?>

<form method="post" action="<?= $link; ?>" id="property_form" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
	<label><?= _name;?></label>
	<p>
		<input type="text" name="name" id="name" class="required" minlength="4" title=' ' value="<?= $row['name'];?>"/>
	</p>
	<label><?= _contact;?></label>
	<p>
		<input type="text" name="contactperson" id="contactperson" class="required" minlength="4" title=' ' value="<?= $row['contactperson'];?>"/>
	</p>
	<label><?= _adress;?></label>
	<p>
		<input type="text" name="street" id="street" class="required" minlength="4" title=' ' value="<?= $row['street'];?>"/>
	</p>
	<label><?= _zip;?></label>
	<p>
		<input type="text" name="zip" id="zip" class="required" minlength="4" title=' ' value="<?= $row['zip'];?>"/>
	</p>
	<label><?= _city;?></label>
	<p>
		<input type="text" name="city" id="city" class="required" minlength="4" title=' ' value="<?= $row['city'];?>"/>
	</p>
	<label><?= _country;?></label>
	<p>
		<? countryDropdown($countries,$row['country']); ?>
	</p>
	<label><?= _email;?></label>
	<p>
		<input type="text" name="email" id="email" class="required email" title=' ' value="<?= $row['email'];?>"/>
	</p>
	<label><?= _website;?></label>
	<p>
		<input type="text" name="website" id="website" class="required" title=' ' value="<?= $row['website'];?>"/>
	</p>
	<label><?= _phone;?></label>
	<p>		 	 	 	 	 	 	
		<input type="text" name="phone" id="phone" class="required" title=' ' value="<?= $row['phone'];?>"/>
	</p>
	<label><?= _fax;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="fax" id="fax" value="<?= $row['fax'];?>"/>
	</p>
	<label>Facebook Link</label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="social_fb" id="social_fb" value="<?= $row['social_fb'];?>"/>
	</p>
	<label>Twitter Link</label>	
	<p>	 	 	 	 	 	 	
		<input type="text" name="social_tw" id="social_tw" value="<?= $row['social_tw'];?>"/>
	</p>
	<label><?= _img;?></label>	
	<p>	 	 	 	 	 	 	
		<input type="file" name="img[]" value=""/>
		<br/><small>best 350x250px | .gif .jpg .png</small>
	</p>
	<label>Logo</label>	
	<p>	 	 	 	 	 	 	
		<input type="file" name="img[]" value=""/>
		<br/><small>best 250x80px | .gif .jpg .png</small>
	</p>
			<input type="hidden" name="created" value="<?= date('Y-m-d H:i:s');?>">
			<input type="hidden" name="id" value="<?= ($row['id']) ? $row['id'] : 1;?>">
			<input type="hidden" name="propertyID" value="<?= $_SESSION['propertyID'];?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_prpty">
	<br/>
	<div style="text-align:center;">
		<input type="submit" class="button_dark" value="<?= _save;?>">
	</div>
</form>