<div id="content_wrapper">
<br/>
	<div class="onecolumn_wrapper">
	 <div class="onecolumn small-column">
	  <div class="content" >
		<?php				
		//message box
		include('includes/messagebox.inc.php'); 
		?>
<!-- Beginn left column -->	
<form method="post" action="?q=3" id="general_settings_form">
	<input type="hidden" name="action" value="save_set">
	<?php
	//get new settings
	$general = querySQL('settings_inc');
	
	foreach ($general as $key => $value){
		$formtext = ucfirst(str_replace(array('_','-'),' ',$key));
		
		if ($key == 'id' || $key == 'property_id') {
			echo "<input type='hidden' name='".$key."' value='".$value."'>";
		}else if ($key == 'timezone'){
			echo "<p><label>".$formtext."</label><br/><div class='option_xl'><div class='text'></div>";
				timezoneDropdown($value);	
			echo "\n</div></p><br/>";

		}else if ($key == 'timeintervall'){
			echo "<p><label>".$formtext."</label><br/><div class='option'><div class='text'></div>";
			echo"<select name='$key' id='$key' class='required' title=' ' size='1' >";
				// make option list
				$i = 15;
				while ($i <= 60) {
					echo "<option value='$i' ";
					echo ($value==$i) ? "selected='selected'" : "";
					echo ">$i</option>\n";
					$i += 15;
				}
			echo "</select>\n</div></p><br/>";
			
		}else if ($key == 'timeformat'){
			echo "<p><label>".$formtext."</label><br/><div class='option'><div class='text'></div>";
			echo"<select name='$key' id='$key' class='required' title=' ' size='1' >";
					// make option list
						echo "<option value='12' ";
						echo ($value==12) ? "selected='selected'" : "";
						echo ">12 h</option>\n";
						echo "<option value='24' ";
						echo ($value==24) ? "selected='selected'" : "";
						echo ">24 h</option>\n";
					echo "</select>\n</div></p><br/>";

		}else if ($key == 'dateformat'){
			echo "<p><label>".$formtext."</label><br/><div class='option'><div class='text'></div>";
				dateformatDropdown($value,0);	
			echo "\n</div></p><br/>";

		}else if ($key == 'datepickerformat'){
			echo "<p><label>".$formtext."</label><br/><div class='option'><div class='text'></div>";
				dateformatDropdown($value,1);	
			echo "\n</div></p><br/>";

		}else if ($key == 'dateformat_short'){
			echo "<p><label>".$formtext."</label><br/><div class='option'><div class='text'></div>";
				dateformatDropdown($value,2);	
			echo "\n</div></p><br/>";

		}else if ($key == 'language'){
			echo "<p><label>"._language."</label><br/><div class='option'><div class='text'></div>";
			getLangList($langTrans, $general['language'], 'enabled');
			echo "</select>\n</div></p><br/>";
		}else if ($key == 'id'){
			echo "<input type='hidden' name='id' value='".$value."'>";
		}elseif($key != 'contactform_background' && $key != 'contactform_color_scheme'){
			echo "<p><label>".$formtext."</label><br/>
				<input type='text' name='".$key."' id='".$key."' value='".$value."' 
				class='required' title=' '/></p><br/>";	
		}
	}
	?>
		<!-- <input type="hidden" name="property_id" value="<?php echo $row->property_id;?>"> -->
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<div class="center">
			<input type="submit" class="button_dark" value="<?php echo _save;?>">
		</div>
	</div></div></div> <!-- end one column -->

	</form><!-- end form -->
	<br class="clear">
</div> <!-- end content wrapper -->
<br/><br/><br/>
