<!-- Begin one column box -->
	<div class="onecolumn margin-4" style="display:none;" id="outlet_detail_slider" >
		
		<div class="header"><h2><?php echo _detail;?></h2></div>

		<div class="content">
		<!-- Begin outlet detail slider -->
		<div class="detail-slider">
			<?php
					$passerby_now_pax = querySQL('passerby_max_pax');
					if ($passerby_now_pax >= $maitre['passerby_max_pax'] && $maitre['passerby_max_pax'] > 0){ 
						$_SESSION['messages'] [] = _sentence_16 ;
					}

					echo "<h4 class='outlet-left'>"._description."</h4> 	
							<p>".$_SESSION['selOutlet']['outlet_description']."</p>";
					echo "<h4 class='outlet-left'>"._cuisine_style."</h4> 	
							<p><span class='bold'>".$cuisines[($_SESSION['selOutlet']['cuisine_style']-1)]."</strong></p>";
					echo "</div><div class='outlet-settings'>";
					echo "<h4 class='outlet-left'>"._settings."</h4>";
					echo "<p>";
					$cando = ( current_user_can( 'Daily-Outlet-Edit' ) ) ? 'enabled' : 'disabled';
					getDayoff_select($dayoff,$maitre['maitre_id'],$cando);
					echo "</p>";
			?>
			<form method="post" action="?q=1" id="edit_maitre_form">
			<label><?php echo _day_comment;?></label>
			<p>
				<?php if ( current_user_can( 'Daily-Outlet-Edit' ) ){
					echo'<input type="text" name="maitre_comment_day" id="maitre_comment_day"  
					value="'.$maitre['maitre_comment_day'].'"/>';
				   }else{
					echo ($maitre['maitre_comment_day']) ? $maitre['maitre_comment_day'] : '--';
				   }
				?>
			</p>
			<?php
			$timestamp = ($maitre['maitre_timestamp']=='') ? " " : " @ ".humanize($maitre['maitre_timestamp']) ;
			echo"<p class='margin-80'><small>".$maitre['maitre_author'].$timestamp."</small></p>";
			?>
			</div>
			<div class="content">
				<label><?php echo _add_seats;?></label>
				<p>
					<?php if ( current_user_can( 'Daily-Outlet-Edit' ) ){
						echo'<input type="text" name="outlet_child_capacity" id="outlet_child_capacity" 
								class="width-50" value="'.$maitre['outlet_child_capacity'].'"/>';
					   }else{
						echo ($maitre['outlet_child_capacity']) ? $maitre['outlet_child_capacity'] : 0;
					   }
					?>
				</p>
				<label><?php echo _add_tables;?></label>
				<p>
					<?php if ( current_user_can( 'Daily-Outlet-Edit' ) ){
						echo'<input type="text" name="outlet_child_tables" id="outlet_child_tables" 
								class="width-50" value="'.$maitre['outlet_child_tables'].'"/>';
					   }else{
						echo ($maitre['outlet_child_tables']) ? $maitre['outlet_child_tables'] : 0;
					   }
					?>
				</p>
				<label>Max. <?php echo _passerby;?></label>
				<p>
					<?php if ( current_user_can( 'Daily-Outlet-Edit' ) ){
						echo'<input type="text" name="outlet_child_passer_max_pax" id="outlet_child_passer_max_pax" 
								class="width-50" value="'.$maitre['passerby_max_pax'].'"/>';
					   }else{
						echo ($maitre['passerby_max_pax']) ? $maitre['passerby_max_pax'] : 0;
					   }
					?>
				</p>
				
				<input type="hidden" name="maitre_id" value="<?php echo $maitre['maitre_id'];?>">
				<input type="hidden" name="maitre_outlet_id" value="<?php echo $_SESSION['selOutlet']['outlet_id'];?>">
				<input type="hidden" name="maitre_author" value="<?php echo $_SESSION['u_fullname'];?>">
				<input type="hidden" name="maitre_date" value="<?php echo $_SESSION['selectedDate']; ?>">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<input type="hidden" name="action" value="save_maitre">
				<?php if ( current_user_can( 'Daily-Outlet-Edit' ) ){
						echo'<input type="submit" class="button_dark" value="'._save.'" class="right-side"/>';
				   }
				?>
			</form>
			</div>
 			<br class="clear"/><br class="clear"/>
 		<!-- End outlet detail slider -->
		</div>
	</div>
<!-- End one column box -->