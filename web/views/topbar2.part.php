<div class="topbar" id="topbar">
        <div class="container">
			<a class="brand" href="<?php echo dirname($_SERVER['PHP_SELF']);?>/main_page.php"><img src="images/logo.png" alt=""/></a>
       
			<ul class="nav">
				<?php
					//outlets submenu
					include('includes/outlets.inc.php'); 
				?>
				<li>
					<a href="main_page.php?p=1" <?php if($_SESSION['page']=='1'){echo "class='active'";} ?> >
						<?php echo _dashboard; ?>	
					</a>
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
          </ul>
			<form class="pull-right" action="main_page.php?p=2&q=1" id="search_form" name="search_form" method="post">
				<input type="text" id="searchquery" name="searchquery" title="<?php echo _search_guest; ?>"/>
				<input type="hidden" name="action" value="search">
			
			<!-- Begin account menu -->
					&nbsp;
					<a href="../PLC/index.php?logout=1" title="Logout">
						<img src="images/icon_logout.png" alt="" class="middle"/>
					</a>
			</form>
			</ul><!-- End account menu -->
	        </div>
    </div>
</div>