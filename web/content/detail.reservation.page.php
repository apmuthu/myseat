<!-- Begin one column box -->
<div class="onecolumn">
	
	<div class="header">
<?php
$rows = querySQL('reservation_info');
//print_r($rows);
	// display rows
	foreach($rows as $row) { 
?>	
		<h2><?php echo _detail;?></h2>
		
		<!-- Begin 2nd level tab -->
		<ul class="second_level_tab">
			<?php if ( $dayoff == 0 && current_user_can( 'Reservation-New' ) ): ?>
			<li>
				<a href="#" id="editToggle" onclick="return false;">
					<?php echo _edit;?>
				</a>
			</li>
			<?php endif ?>
			<li>
				<a href="?p=2&outletID=<?php echo $_SESSION['outletID']; ?>" class="button_dark">
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
		<div id="show"
		<?php if ($resedit == 'ON'){
			echo' style="display:none;"';
		}
		?>
		>
					<?php include('includes/reservation_detail.inc.php'); ?>
		</div>
		<div id="edit"
		<?php if ($resedit == 'OFF'){
				echo' style="display:none;"';
		}
		?>
		>
					<?php include('includes/reservation_form.inc.php'); ?>
		</div>
		<br class="clear">
	</div> <!-- end content wrapper -->
	<!-- End detail -->	
</div>
<div class="onecolumn">
	<div class="header">
		<h3><?php echo _info;?></h3>
	</div>
  <div id="content_wrapper">
	<div class="content detailbig">
<?php
// INFO: guest history
// count total visits
$visits = querySQL('reservation_visits');
// last time guest visits outlet
$visits2 = querySQL('reservation_last_visit');
$lastvisit = ($visits2>0 && $visits2!='1970-01-01') ? date("d.M Y", strtotime($visits2)) : "--";

// collect history infos about guest
$history = 	querySQL('reservation_history');	

echo "<label>"._visits."</label><p>".$visits."</p>";
echo "<label>"._last_visit."</label><p>".$lastvisit."</p>";
echo "<label>"._history."</label>";
 
echo "<p><ul class='global'>";
if(count($history)>0){
	foreach ($history as $row) {
		if (trim($row->reservation_notes)!=''){
			echo "<li>".$row->reservation_notes."</li>";
		}
	}
}
echo "</ul></p>";

//reservation history
echo "<br/><label>"._changes."</label><p><div class='option_xl'><div class='text'></div>";
$res_history = 	querySQL('res_history');
echo"<select name='history' size='1' >\n";
foreach ($res_history as $row) {
	echo "<option>".$row->author." : ".$row->timestamp."</option>";
}
echo "</select></div></p><br/>";
 
?>
	</div>
  </div>
</div>
<?php } ?>
<br/>
<br style='clear:both;'/>
</div>
<!-- End one column box -->