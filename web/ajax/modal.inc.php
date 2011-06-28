<!-- Modal Message "Delete Reservation" -->
<div style="display:none">
<div id="modalsecurity" style='background:#FFF;' width='400px;'>
	<h2><?php echo _cancelled; ?></h2>
	<br/>
	<p>
		<?php echo _sentence_2; ?>
	</p>
	<div id='error_msg'></div><br/>
	<label><?php echo _author;?></label>
	<input type="text" id="conf-author" value=""/>
	<br/><br/><br/>
	<p style='text-align:center;'>
		<button type='submit' class='send-button' id='button_sg' name='single' value ='single'>
			<?php echo ucfirst(_delete);?>
		</button>
		<button type='submit' class='send-button' id='button_al' name='all' value='all'>
			<?php echo ucfirst(_delete_all_entries);?>
		</button>
		<button onclick="$.fancybox.close();"> <?php echo _no_;?> </button>
	</p>
	<br/>
</div>
</div>

<!-- Modal Message "Tablenumber" -->
<a id="modaltabletrigger" href="#modaltable"></a>
<div style="display:none">
<div id="modaltable" style='background:#FFF;' width='400px;'>
	<h2><?php echo _information; ?></h2>
	<br/>
	<p class='center'><span class='bold'>
		<?php echo _sentence_5; ?>
	</strong></p>
	<br/><br/>
</div>
</div>

<!-- Modal Message "Delete" -->
<div style="display:none">
<div id="modaldelete" style='background:#FFF;' width='400px;'>
	<h2><?php echo _cancelled; ?></h2>
	<br/>
	<p>
		<?php echo _sentence_2; ?>
	</p>
	<p style='text-align:center;'>
		<button type='submit' class='send-button' id='button_sg' name='single' value ='single'>
			<?php echo ucfirst(_delete);?>
		</button>
		<button onclick="$.fancybox.close();"> <?php echo _no_;?> </button>
	</p>
	<br/>
</div>
</div>