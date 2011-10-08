<!-- Begin top bar -->
<div id="top_bar">
	
	
	<!-- Begin logo -->
	<div class="logo">
		<a href="<?php echo dirname($_SERVER['PHP_SELF']);?>/main_page.php"><img src="images/logo.png" alt=""/></a>
	</div>
	<!-- End logo -->
	
		<ul class="nav margin-left-40">
			<li>
				<a href="main_page.php?p=1" <?php if($_SESSION['page']=='1'){echo "class='active'";} ?> >
					<?php echo _dashboard; ?>
				</a>
			</li>
			<li>
				<a href="main_page.php?p=2" <?php if($_SESSION['page']=='2'){echo "class='active'";} ?> >
					<?php echo _outlets; ?>	
				</a>
				<?php
					//popup submenu
					include('includes/outlets.inc.php'); 
				?>
			</li>
			<?php if ( current_user_can( 'Page-Statistic' ) ): ?>
			<li>
				<a href="main_page.php?p=3" <?php if($_SESSION['page']=='3'){echo "class='active'";} ?> >
					<?php echo _statistics; ?>
				</a>
			</li>
			<?php endif ?>
			<?php if ( current_user_can( 'Page-Export' ) ): ?>
			<li>
				<a href="main_page.php?p=4" <?php if($_SESSION['page']=='4'){echo "class='active'";} ?> >
					<?php echo _export; ?>
				</a>
			</li>
			<?php endif ?>
			<?php if ( current_user_can( 'Page-System' ) ): ?>
			<li>
				<a href="main_page.php?p=6&btn=1" <?php if($_SESSION['page']=='6'){echo "class='active'";} ?> >
					<?php echo _system; ?>
				</a>
			</li>
			<?php endif ?>
	<!--		<li>
				<a href="http://<?php echo $_SERVER['HTTP_HOST'].substr(dirname($_SERVER['PHP_SELF']),0,-4);?>/contactform/index.php?so=ON&prp=<?php echo $_SESSION['property'];?>&outletID=<?php echo $_SESSION['outletID'];?>" target="blank">
				<?php echo _online;?>
				</a>
			</li>
			<li>
				<a href="?p=5" <?php if($_SESSION['page']=='5'){echo "class='active'";} ?> >
					<?php echo _info; ?>
				</a>
			</li>
	-->
		</ul>
	
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