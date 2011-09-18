<!-- Begin main menu -->
<div id="menu_wrapper">

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
	
	<div id="search">
		<form action="main_page.php?p=2&q=1" id="search_form" name="search_form" method="post">
			<p><input type="text" id="searchquery" name="searchquery" title="<?php echo _search_guest; ?>" class="search noshadow"/></p>
			<input type="hidden" name="action" value="search">
		</form>
	</div>
	
</div>
<!-- End main menu -->

<br class="clear"/>

<!-- Begin content -->
<div id="content_wrapper">