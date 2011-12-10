<!-- Begin example table data -->
<table class="global width-100" cellpadding="0" cellspacing="0">
	<thead>
	    <tr>
			<th>ID</th>
			<th><?php echo _login; ?></th>
			<th><?php echo _name; ?></th>
			<th><?php echo _email; ?></th>
			<th><?php echo _type; ?></th>
			<th>IP</th>
			<th><?php echo _time; ?></th>
			<th><?php echo _active; ?></th>
			<th></th>
	    </tr>
	</thead>
	<tbody>
		<?php

		$users = querySQL('db_prp_users');
		
		if ($users) {
			foreach($users as $row) {
			echo "<tr id='user-".$row->userID."'>";
					
			echo"<td>".$row->userID."</td>
			<td><span class='bold'><a href='?p=6&q=2&btn=3&userID=".$row->userID."'>".$row->username."</a></strong></td>
			<td>".$row->realname."</td>
			<td>".$row->email."</td>
			<td>".$roles[$row->role]."</td>
			<td><small>".$row->last_ip."</small></td>
			<td><small>".$row->last_login."</small></td>
			<td>";
			
				if($row->active > 0){
					$hidden1 = "style='display:block; float:left;'";
					$hidden2 = "style='display:none; float:left;'";
				}else{
					$hidden1 = "style='display:none; float:left;'";
					$hidden2 = "style='display:block; float:left;'";
				}
		
			echo"<a href='#this' id='disable-".$row->userID."' class='modalactivate' ".$hidden1.">
				<img src='../web/images/icons/ui-check-box.png' alt='Disable' title='Disable' /></a>";
			echo"<a href='#this' id='enable-".$row->userID."' class='modalactivate' ".$hidden2.">
				<img src='../web/images/icons/ui-check-box-uncheck.png' alt='Enable' title='Enable' /></a>";
			echo "</td>
		    		
		    		<td>
				    <a href='#modaldelete' name='users' id='".$row->userID."' class='deletebtn'>
					<img src='images/icons/delete_cross.png' alt='"._cancelled."' class='help' title='"._delete."'/>
					</a>
		    	</td>
			</tr>";
			}
		}
		?>
	</tbody>
</table>
<!-- End example table data -->