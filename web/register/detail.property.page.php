<?php if($settings['googlemap_key'] != ""){?>
    <!-- Google Map Key -->
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $settings['googlemap_key']; ?>" type="text/javascript" ></script>
    <script type="text/javascript" src="js/jquery.gmap-1.1.0-min.js"></script>
<?php } ?>
<?php
// Get property details
    $_SESSION['propertyID'] = ($_SESSION['propertyID']=="") ? $_SESSION['property'] : $_SESSION['propertyID'];
    $row = querySQL('property_info');
?>

<!-- Begin detail -->
<div class="content">
	<div id="content_wrapper">
		<br/>
		<div class="onecolumn_wrapper">
			<div class="onecolumn" style="margin-right:5%; margin-left:5%;">
			  <div class="content" >
				<div id="show">
							<?php 
								include('register/property_detail.inc.php'); 
							?>
				</div>
				<div id="edit" style="display:none;">
							<?php 
								include('register/property_form.inc.php'); 
							?>
				</div>
			  </div>
			</div>
		</div> <!-- end content wrapper -->
  	</div> <!-- End detail -->	
</div>
<!-- End content -->