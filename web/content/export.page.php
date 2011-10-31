<?php
session_start();
?>
<!-- Begin one column box -->
<div class="onecolumn">
	<div class="header">
		<h2><?php echo _export;?></h2>
		
		<!-- Begin 2nd level tab -->
		<ul class="second_level_tab">
			<li>
				<a href="?p=2" class="button_dark">
					<?php echo _back;?>
				</a>
			<li/>
		</ul>
		<!-- End 2nd level tab -->
		
	</div>
	
	
	<div id="content_wrapper">
	<br/>
		<div class="onecolumn_wrapper">
		 <div class="onecolumn smallcontent">
		  <div class="content" >
			
			<table class="general">
			<form name="export_form" id="export_form" method="post" action="classes/export.class.php" accept-charset="UTF-8">	
			<tr>
				<td>
					<th></th>
					<label class='leftside'><?php echo _date; ?></label>
					<br class="clear"/>
					<div style="float:left;">
						<div class="text" id="s_datetext"><?php echo $_SESSION['selectedDate_user']; ?></div>
						<input type="text" id="s_datepicker"/>
						<input type="hidden" name="s_dbdate" id="s_dbdate" value="<?php echo $_SESSION['selectedDate']; ?>"/>
		    	    </div>
					
					<strong class='leftside'>&nbsp;&nbsp; <?php echo _till;?> &nbsp;&nbsp;</strong>  
					
					<div style="float:left;">
						<div class="text" id="e_datetext"><?php echo $_SESSION['selectedDate_user']; ?></div>
						<input type="text" id="e_datepicker"/>
						<input type="hidden" name="e_dbdate" id="e_dbdate" value="<?php echo $_SESSION['selectedDate']; ?>"/>
		    	    </div>
				</td>
			</tr>
			<tr>
				<th><span class='bold'><?php echo _outlets; ?></strong></th>
				<td>
							<?php getOutletList(0,'enabled'); ?>
				</td>
			</tr>
			<tr>
				<th><span class='bold'><?php echo _type; ?></strong></th>
				<td>
							<?php getTypeList();?>
				</td>
			</tr>
			<tr>
				<td>
					<br/><br/>
				<input type="hidden" name="action" value="export">
				<input type="submit" class="button_dark" value="<?php echo _export;?>">
				</td>
			</tr>
			</form>
			</table> <!-- close table -->
			
			
		  </div>
		 </div>
		</div>
	</div>
	<br/>
</div>

<br class="clear"/><br/>