
<?php if ( current_user_can( 'Property-Overview' ) ): ?>
<!-- Begin property table data -->
<table class="global" style="margin:0px 0px;" cellpadding="0" cellspacing="0">
	<thead>
	    <tr>
			<th style="width:30%"><?php echo _name; ?></th>
			<th style="width:20%"><?php echo _adress; ?></th>
			<th style="width:20%"><?php echo _area_code; ?></th>
			<th style="width:10%"><?php echo _contact; ?></th>
			<th style="width:5%"><?php echo _email; ?></th>
			<th style="width:5%"><?php echo _phone; ?></th>
			<th style="width:5%"><?php echo _fax; ?></th>
			<th style="width:5%"><?php echo _delete; ?></th>
	    </tr>
	</thead>
	<tbody>
		<?php

		$properties = querySQL('all_properties');
		
		if ($properties ) {
			foreach($properties as $row) {
			echo "<tr id='property-".$row->id."'>";
		
			echo"<td><span class='bold'><a href='?p=5&propertyID=".$row->id."'>".$row->name."</a></strong></td>
			<td>".$row->street."</td>
			<td>".$row->zip . "-" . $row->city."</td>
			<td>".$row->contactperson."</td>
			<td>".$row->email."</td>
			<td>".$row->phone."</td>
			<td>".$row->fax."</td>
			<td>
				<a href='#modaldelete' name='properties' id='".$row->id."' class='deletebtn'>
				<img src='images/icons/delete_cross.png' alt='"._cancelled."' class='help' title='"._delete."'/>
				</a>
		    	</td>
			</tr>";
			}
		}
		?>
	</tbody>
</table>
<!-- End property table data -->
<?php endif ?>