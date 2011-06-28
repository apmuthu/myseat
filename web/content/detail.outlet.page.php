<!-- Begin one column box -->
<div class="onecolumn">
	
	<div class="header">
<?php
$rows = querySQL('outlet_info');

	// display rows
	foreach($rows as $row) { 
?>	
		<h2><?php echo _detail;?></h2>

		<!-- Begin 2nd level tab -->
		<ul class="second_level_tab">
			<li>
				<a href="#" id="editToggle" onclick="return false;">
					<?php echo _edit;?>
				</a>
			</li>
			<li>
				<a href="?p=6&q=1" class="button_dark">
					<?php echo _back;?>
				</a>
			<li/>
		</ul>
		<!-- End 2nd level tab -->	
	</div>
	<div class="content">
	<div id="content_wrapper">
	<br/>
	<!-- Begin detail -->
<div id="show">
	<div class="twocolumn_wrapper">
	 <div class="twocolumn">
			<?php 
				include('includes/outlets_detail.inc.php'); 
			?>
	 </div>
	</div> <!-- end detail -->
</div>
<div id="edit" style="display:none;">
	<div class="twocolumn_wrapper">
	 <div class="twocolumn">
			<?php 
				include('includes/outlets_form.inc.php'); 
			?>
	 </div>
	</div> <!-- end detail -->
</div>
		<br class="clear">
		<br/>
			<a href="?p=6&q=1" class="button_dark"><input type="button" class="button_dark" value="<?php echo _back;?>"/></a>
		<br/>
	</div> <!-- end content wrapper -->
	<!-- End detail -->	
</div></div>
<!-- End one column box -->
<?php } ?>