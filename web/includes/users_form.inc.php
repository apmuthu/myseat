<?php
if ($_SESSION['button']==2 || $_SESSION['page'] == 7) {
	$row = "";
}else{
	$row = querySQL('user_data');
	$row['password'] = 'EdituseR';	
}

// redirect to right page
if ($_SESSION['button']==2 || $_SESSION['button']==3) {
	$link = "main_page.php?p=6&q=2&btn=1";
}
if ($_SESSION['page'] == 7){
	$link = "properties.php?p=8";
}
?>

<form method="post" action="<?php echo $link; ?>" id="user_form">
	<label>
		<?php
		if ($_SESSION['page']==7){echo $roles[2]." ";}
		echo _name;
		?>
	</label>
	<p>
		<input type="text" name="username" id="username" class='required' minlength='3' maxlength='12' title=' ' value="<?php echo $row['username'];?>"/>
		<div id="status"></div>
	</p>
		<label>
		<?php
		if ($_SESSION['page']==7){echo $roles[2]." ";}
		echo _password;
		?>
	</label>
	<p>
		<input type="password" name="password" id="password" class="required" minlength="6" maxlength='12' title=' ' value="<?php echo $row['password'];?>"/>
	</p>
	<label><?php echo _retype." "._password;?></label>
	<p>
		<input type="password" name="password2" id="password2" class="required" minlength="6" maxlength='12' title=' ' value="<?php echo $row['password'];?>"/>
	</p>
	<label><?php echo _email;?></label>
	<p>
		<input type="text" name="email" id="email" class="required email" title=' ' value="<?php echo $row['email'];?>"/>
	</p>
	<label><?php echo _type;?></label>
	<p>
			<?php
			if(($_SESSION['page'] != 7 && $row['userID'] !='') || ($_SESSION['page'] == 6 && $row['userID'] =='')){	
				echo "<select name='role' id='role' size='1'>\n";
				//set role
				$role = ($row['role']) ? $row['role'] : 6;
				
				// only allow to create roles smaller than your own
				foreach($roles as $key => $value) {
						//build dropdown
						if($key>=$_SESSION['role']){
							echo "<option value='".$key."' ";
							echo ($key==$role) ? "selected='selected'" : "";
							echo ">".$value."</option>\n";
						}
				}
				
				echo "</select>";
			}else{
				// creating a new property and admin
				echo "<span class='bold'>".$roles[2]."</strong><input type='hidden' name='role' id='role' value='2'>";
			}
			?>		
	</p>
	<br/>
				<?php
				if ($_SESSION['page']==7){
					echo "<input type='hidden' name='active' value='1'>";
				}
				?>
			<input type="hidden" name="created" value="<?php echo date('Y-m-d H:i:s');?>">
			<input type="hidden" name="userID" value="<?php echo ($row['userID']) ? $row['userID'] : 0 ;?>">
			<input type="hidden" name="property_id" value="<?php echo ($_SESSION['property']) ? $_SESSION['property'] : 1 ;?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="hidden" name="action" value="save_usr">
	<br/>
	<div class="center">
		<input type="submit" class="button_dark" value="<?php echo _save;?>">
	</div>
	<br/>
	<?php 
		include('content/rolesgrid.php');
	?>
	<br/>
</form>
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	$("#username").change(function() {
	var usr = $("#username").val();
	if(usr.length >= 3) {
	  $("#status").html('<img align="absmiddle" src="images/ajax-loader.gif" />');
	
	  $.ajax({
		type: "POST",
		url: "ajax/check_username.php",
		data: "username="+ usr,
		success: function(msg){
			
			$("#status").ajaxComplete(function(event, request){
				if(msg.length <= 4){
					$("#username").removeClass('error');
					$("#username").addClass('blur');
					$(this).html(' <img align="absmiddle" src="images/icons/icon_accept.png" /> ' + msg);
				}else{
					$("#username").removeClass('blur');
					$("#username").addClass('error');
					/* $("#username").val(''); */
					$(this).html(msg);
				}
			});
		}
	  });
	}
  });
	
});
	//-->
</script>