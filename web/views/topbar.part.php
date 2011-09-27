<!-- Begin top bar -->
<div id="top_bar">
	
	
	<!-- Begin logo -->
	<div class="logo">
		<a href="<?php echo dirname($_SERVER['PHP_SELF']);?>/main_page.php"><img src="images/logo.png" alt=""/></a>
	</div>
	<!-- End logo -->
	
	<!-- Begin account menu -->
	<div class="account">
		<div class="detail">
			<?php
			if (!$_SESSION['prp_name']) {
				$_SESSION['prp_name'] = querySQL('db_property');
			}
			
			$filename = substr(dirname(__FILE__),0,-9)."xt-admin";
			
			if($this_page != "property"){
				echo "<img src='images/icon_user.png' alt='User:' class='middle'/><a href='";
				if ($_SESSION['role']=='1' && file_exists($filename)) {
					echo"../xt-admin/index.php";				
				}
				echo "'><span class='bold'> ".$_SESSION['u_name']."</span></a>, ".$roles[$_SESSION['role']]." - ".$_SESSION['prp_name'];
			}
			?>
		</div>
		<ul class="icon">
			<!--
			<li>
				<a href="#" title="Message">
					<img src="images/icon_message.png" id="message" alt="message" class="middle tipsy" original-title="New message"/>
				</a>
			</li>
			<li>
				<a href="#" title="Setting">
					<img src="images/icon_setting.png" alt="" class="middle"/>
				</a>
			</li>
			-->
			<li>
			<?php echo '<small>'.$sw_version."</small>&nbsp;";?>
			</li>
			<li>
				<a href="../PLC/index.php?logout=1" title="Logout">
					<img src="images/icon_logout.png" alt="" class="middle"/>
				</a>
			</li>
		</ul>
	</div>
	<!-- End account menu -->
	
	
</div>
<!--End top bar -->