<!-- Begin top bar -->
<div id="top_bar">
	
	
	<!-- Begin logo -->
	<div class="logo">
		<a href="<?= dirname($_SERVER['PHP_SELF']);?>"><img src="images/logo.png" alt=""/></a>
	</div>
	<!-- End logo -->
	
	<!-- Begin account menu -->
	<div class="account">
		<div class="detail">
			<?
			if (!$_SESSION['prp_name']) {
				$_SESSION['prp_name'] = querySQL('db_property');
			}
			
			
			if($this_page != "property"){
				echo "<img src='images/icon_user.png' alt='User:' class='middle'/><a href=''><span class='bold'> ".$_SESSION['u_name']."</span></a>, ".$roles[$_SESSION['role']]." - ".$_SESSION['prp_name'];
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
				<a href="?logout=1" title="Logout">
					<img src="images/icon_logout.png" alt="" class="middle"/>
				</a>
			</li>
		</ul>
	</div>
	<!-- End account menu -->
	
	
</div>
<!--End top bar -->