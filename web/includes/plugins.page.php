<?php
	// get all plugins from folder and read the headers
	$plugin_list = new phphooks ( );
	$plugin_headers = $plugin_list->get_plugins_header ();
?>
<table class="global width-100" cellpadding="0" cellspacing="0" id="active-plugins-table">
	<thead>
		<tr>
			<th scope="col">Plugin</th>
			<th scope="col">Version</th>
			<th scope="col"><?php echo _description;?></th>
			<th scope="col"><?php echo _edit;?></th>
		</tr>
	</thead>

	<tfoot>
	</tfoot>

	<tbody class="plugins">
<?php

foreach ( $plugin_headers as $plugin_header ) {

	$field = $plugin_header['filename'];
	$action = querySQL('get_plugins');

	?>
		<tr <?php
	if ($action==1)
		echo "class='row-active'";
	?>>
			<td><a
				href="<?php
	echo $plugin_header ['PluginURI'];
	?>"
				title="<?php
	echo $plugin_header ['Title'];
	?>"><?php
	echo $plugin_header ['Name'];
	?></a></td>
			<td><?php
	echo $plugin_header ['Version'];
	?></td>
			<td>
			<p><?php
	echo $plugin_header ['Description'];
	?> by <a href="<?php
	echo $plugin_header ['AuthorURI'];
	?>"
				title="Visit author homepage"><?php
	echo $plugin_header ['Author'];
	?></a>.</p>
			</td>
			<td>
				<?php
	if ($action==0){
		echo '<a href="?q=7&action=plugins&button=activate&filename='.$plugin_header['filename'].'">
			<img src="images/icons/play.png"/></a>';
	}else{
		echo '<a href="?q=7&action=plugins&button=deactivate&filename='.$plugin_header['filename'].'">
			<img src="images/icons/pause.png"/></a>';
	}
	?>
			</td>
		</tr>
<?php
}
?>
	</tbody>
</table>