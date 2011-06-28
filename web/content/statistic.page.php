<?php
/**
 *
 *  Gets the first weekday of that month and year
 *
 *  @param  int   The day of the week (0 = sunday, 1 = monday ... , 6 = saturday)
 *  @param  int   The month (if false use the current month)
 *  @param  int   The year (if false use the current year)
 *
 *  @return int   The timestamp of the first day of that month
 *
 **/ 
function get_first_day($day_number=1, $month=false, $year=false)
{
  $month  = ($month === false) ? strftime("%m"): $month;
  $year   = ($year === false) ? strftime("%Y"): $year;
 
  $first_day = 1 + ((7+$day_number - strftime("%w", mktime(0,0,0,$month, 1, $year)))%7);

  return mktime(0,0,0,$month, $first_day, $year);
}

//prepare selected Date
list($sy,$sm,$sd) = explode("-",$_SESSION['selectedDate']);

// Bar Plot month
$datamonth = array();
$i=1;
while ($i<=12){
	//month, without leading zeros
	if ( strlen($i)==1){
		$_SESSION['statistic_month']="0".$i;
	}else{
		$_SESSION['statistic_month']=$i;
	}

	$statistic = querySQL('statistic_month');
	$statistic2 = querySQL('statistic_month_last');
	
	foreach ($statistic as $row) {
		$datamonth[$i-1] = ($row->paxsum) ? $row->paxsum : 0; 
		$labelmonth[] = $_SESSION['statistic_month']; 
	}
	foreach ($statistic2 as $row) {
		$datamonth2[$i-1] = ($row->paxsum) ? $row->paxsum : 0; 
	}
	
$i++;
}
// Set month from selected date
$_SESSION['statistic_month'] = $sm;

// Bar Plot def guests per day/week
$data1 = array();
$labels = array();
$i=0;
while ($i<=6){
	$labeldate=date('d.m.',mktime(0,0,0,$sm,$sd-$i,$sy));
	$_SESSION['statistic_week'] = date('Y-m-d',mktime(0,0,0,$sm,$sd-$i,$sy));
	
	$statistic = querySQL('statistic_week_def');
	foreach ($statistic as $row) {
		$statistic_week_def[$i] = ($row->paxsum) ? $row->paxsum : 0; 
	}
	$labels[] = $labeldate;
	
$i++;
}

// Bar Plot Guest by weekday/month
$data_wk = array();
$label_wk = array();

	$statistic = querySQL('statistic_weekday');

	foreach ($statistic as $key => $value) {
		foreach($value as $paxsum){
			$label_wk[] = $key;
			$data_wk[] = $paxsum;
		}
	}

// Cake Plot Guest type/month
$datapie = array();
$labelpie = array();

	$pie_data = querySQL('statistic_type');

	foreach ($pie_data as $row) {
		$labelpie[] = $row->reservation_hotelguest_yn;
		$datapie[] = $row->paxsum;
	}

?>

<!-- Begin one column box -->
<div class="onecolumn">
	<div class="header">
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
	<div class="content">
		<h1 class="stats_header_text">
			<?php echo _statistics." "._for_." ".strftime("%B",strtotime($_SESSION['selectedDate']))." "._for_." ".$_SESSION['selOutlet']['outlet_name'];?>
		</h1>
		<table id="numbers" class="data center" cellpadding="0" cellspacing="20">
			<tbody>
				<tr>
					
					<td width="400px">
					 <div class="stats_section_title">
						<span><?php echo strtoupper(_pax."/"._year);?></span>
					 </div>
					 <div class="stats_section_content">
						<?
						 $pax_outlet = querySQL('statistic_guest_year');
						 $pax_prpty = querySQL('statistic_all_guest_year');
						//prevent division by zero 
						$pax_prpty = ($pax_prpty==0) ? 1 :$pax_prpty;
						 $pax_outlet = ($pax_outlet==0) ? 1 :$pax_outlet;
						?>
						<span class="bigger_number"><?php echo $pax_outlet;?></span>&nbsp;<span class='stats_small_text'>(<?php echo round((100/$pax_prpty*$pax_outlet),1);?>%)</span>
					 </div>
					</td>
					
					<td width="200px">
					 <div class="stats_section_title">
						<span><?php echo strtoupper(_cancelled."/"._year);?></span>
					 </div>
					 <div class="stats_section_content">
						<span class="big_number"><?php echo querySQL('statistic_cxl_year');?></span>
					 </div>
					</td>
					
					<td width="200px">
					 <div class="stats_section_title">
						<span><?php echo strtoupper(_wait_list."/"._year);?></span>
					 </div>
					 <div class="stats_section_content">
						<span class="big_number"><?php echo querySQL('statistic_wait_year');?></span>
					 </div>
					</td>
					
					</tr><tr>
					
					<td>
					 <div class="stats_section_title">
						<span><?php echo strtoupper(_webform."/"._year);?></span>
					 </div>
					 <div class="stats_section_content">
						<span class="bigger_number"><?php echo querySQL('statistic_online_year');?></span>
					 </div>
					</td>
					
					<td colspan="2">
					 <div class="stats_section_title">
						<span><?php echo strtoupper(_top." 7 "._trsources);?></span>
					 </div>
					 <div class="stats_section_content">
						<ul>
							<?php
							$referer_data = querySQL('statistic_referer');
							foreach ($referer_data as $row) {
								if ($row->reservation_referer!='') {
									echo "<li>".$row->reservation_referer."&nbsp;
									<span class='stats_small_text'>(".$row->total."&times;)</span></li>";
								}
							}
							?>
						</ul>
					 </div>
					</td>
					
					<td><!-- empty --></td>
					
					</tr><tr>
					
					<td>
					 <div class="stats_section_title">
						<span><?php echo strtoupper(_top." 7 "._pax."/"._year);?></span>
					 </div>
					 <div class="stats_section_content">
						<ul>
						<?php
						$top_guests = querySQL('statistic_top5_guest_year');
						foreach ($top_guests as $top_guest) {
							echo "<li>".$top_guest->reservation_guest_name."&nbsp;<span class='stats_small_text'>(".$top_guest->total."&times;)</span></li>";
						}
						?>
						</ul>
					 </div>
					</td>
					
					<td><!-- empty --></td>
					
					<td>
					 <div class="stats_section_title">
						<span>&empty; <?php echo strtoupper(_days."/"._reservations);?></span>
					 </div>
					 <div class="stats_section_content">
						<span class="biggest_number"><?php echo querySQL('statistic_res_days');?></span>
					 </div>
					</td>
					
				</tr>
			</tbody>
		</table>
		<br/>
		<div id="graph_wrapper1" class="graph_wrapper"></div>
		<table id="graph_week" class="data" style="display:none" cellpadding="0" cellspacing="0" width="100%">
			<caption><?php echo _occupancy_per_week;?></caption>
			<thead>
				<tr>
					<td class="no_input">&nbsp;</td>
					<?php
					foreach ($labels as $value) {
						echo "<th>".$value."</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo _days;?></th>
					<?php
					foreach ($statistic_week_def as $value) {
						echo "<td>".$value."</td>";
					}
					?>
				</tr>
			</tbody>
		</table>
		<br/>
		<div id="graph_wrapper4" class="graph_wrapper"></div>
		<table id="graph_weekday" class="data" style="display:none" cellpadding="0" cellspacing="0" width="100%">
			<caption><?php echo _occupancy_per_week." / "._days;?></caption>
			<thead>
				<tr>
					<td class="no_input">&nbsp;</td>
					<?php
					foreach ($label_wk as $value) {
						echo "<th>".strftime("%A", get_first_day($value, $_SESSION['statistic_month'], $_SESSION['selectedDate_year']))."</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo _days;?></th>
					<?php
					foreach ($data_wk as $value) {
						echo "<td>".$value."</td>";
					}
					?>
				</tr>
			</tbody>
		</table>
		<br/>
		<div id="graph_wrapper2" class="graph_wrapper"></div>
		<table id="graph_month" class="data" style="display:none;" cellpadding="0" cellspacing="0" width="100%">
			<caption><?php echo _occupancy_per_month;?></caption>
			<thead>
				<tr>
					<td class="no_input">&nbsp;</td>
					<?php
					foreach ($labelmonth as $value) {
						echo "<th>".$value."</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo $_SESSION['selectedDate_year'];?></th>
					<?php
					foreach ($datamonth as $value) {
						echo "<td>".$value."</td>";
					}
					?>
				</tr>
				<tr>
					<th><?php echo $_SESSION['selectedDate_year']-1;?></th>
					<?php
					foreach ($datamonth2 as $value) {
						echo "<td>".$value."</td>";
					}
					?>
				</tr>
			</tbody>
		</table>
		<div id="graph_wrapper3" class="graph_wrapper"></div>
		<table id="graph_type" class="data" style="display:none;" cellpadding="0" cellspacing="0" width="100%">
			<caption><?php echo _guest_type_per_month;?></caption>
			<thead>
				<tr>
					<?php
					foreach ($labelpie as $value) {
						echo "<th>".$value."</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($pie_data as $row) {
					echo "<tr><th scope='row'>".$row->reservation_hotelguest_yn."</th>\n";
					echo "<td>".$row->paxsum."</td>\n</tr>\n";
				}
				?>
			</tbody>
		</table>
		<br/>
		<small>Inspired by Nicholas Felton's fantastic <a href="http://www.feltron.com" target="_blank">Feltron Annual Report</a></small>
	</div>
</div>
<br class="clear"/><br/>