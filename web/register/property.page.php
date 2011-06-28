	<!-- Begin 1st level tab -->
	<ul class="first_level_tab">
		<?php if ( current_user_can( 'Property-Overview' ) ): ?>
		<li>
			<a href="?p=1" <?php if ($_SESSION['page'] == 1 || $_SESSION['page'] == 6) { echo " class='active'";}?> >
				<?php echo _property; ?>
			</a>
		</li>
		<?php endif ?>
		<?php if ( current_user_can( 'Property-New' ) ): ?>
		<li>
			<a href="?p=2" <?php if ($_SESSION['page'] == 2 || $_SESSION['page'] == 7) { echo " class='active'";}?> >
				<?php echo _create; ?>
			</a>
		</li>
		<?php endif ?>
	</ul>	
	<!-- End 1st level tab -->
	
	<br class="clear"/>
	
	<!-- Begin one column box -->
	<div class="onecolumn">
		
		<div class="header">
			<div class="description">
				<?php
				if ($_SESSION['page'] == 1 || $_SESSION['page'] == 6) { 
					echo "<h3>"._property." "._overview."</h3>";
				}else if ($_SESSION['page'] == 2) { 
					echo "<h3>"._create." "._property."</h3>";
				}else if ($_SESSION['page'] == 5) { 
					echo "<h3>"._property." "._detail."</h3>";
				}else if ($_SESSION['page'] == 7) { 
					echo "<h3>"._create." ".Administrator."</h3>";
				}
				?>
			</div>
			
			<!-- Begin 2nd level tab -->
			<ul class="second_level_tab" style="line-height:1.4em">
				<?php if ( $_SESSION['page'] == 5 ): ?>
					<li>
						<a href="?p=1" <?php if ($_SESSION['page'] == 1 ) { echo " class='active'";}?> >
							<?php echo _overview; ?>
						</a>
					</li>
					<li>
						<a href="#" id="editToggle" onclick="return false;">
							<?php echo _edit;?>
						</a>
					</li>
				<?php endif ?>
				<li>
					<?php
						if ($_SESSION['page'] != 1) {
							echo "<a href='properties.php?p=6'>"._back."</a>";
						}
					?>
					
				</li>
			</ul>
			<!-- End 2nd level tab -->
		
		</div>
		
		<!-- Begin nomargin -->
		<div class="content nomargin">
			
			<?php
			// ** content
			switch($_SESSION['page']){
				case '1':
					// property overview
					include('register/property_grid.inc.php');
				break;
				case '2':
					// create property
					include('register/property_new.inc.php');
				break;
				case '5':
					// property detail
					include('register/detail.property.page.php');
				break;
				case '6':
					// property overview
					include('register/property_grid.inc.php');
				break;
				case '7':
					// jump to new user after adding a property
					include('includes/users_new.inc.php');
				break;
				case '8':
					// jump to success page after registration process
					include('register/success.inc.php');
				break;
			}
			?>
			
			<br class="clear"/>
		
		</div>
		<!-- End nomargin -->
</div>
<br/>

<br/>
<br class="clear"/>