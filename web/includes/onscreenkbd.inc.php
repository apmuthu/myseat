<?php 
$osk = (isset($settings['OnScreenKbd']) && $settings['OnScreenKbd']);
$osk_img = '';
if ($osk) { 
	$osk_img = '<img src="../web/images/keyboard.png" class="kbdicon">';
?>
<!-- Start of OnScreen Keyboard Code by Ap.Muthu <apmuthu@usa.net> -->

	<!-- jQuery & jQuery UI + theme (required) -->
	<link href="../web/css/jquery-ui.css" rel="stylesheet">
	<script src="../web/js/jquery-1.4.4.min.js"></script>
	<script src="../web/js/jquery-ui-1.8.10.custom.min.js"></script>

	<!-- keyboard widget css & script (required) -->
	<link href="../web/css/keyboard.css" rel="stylesheet">
	<script src="../web/js/jquery.keyboard.min.js"></script>

	<!-- keyboard extensions (optional) -->
	<script src="../web/js/fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
	<script src="../web/js/jquery.keyboard.extension-typing.js"></script>

	<!-- initialize keyboard (required) -->
	<script>
	$(function(){
		$('.qwerty').keyboard({
			openOn : null,
			stayOpen : true,
			layout:"qwerty",
			customLayout: {
				'default': [
					"q w e r t y {bksp}",
					"s a m p l e {shift}",
					"{accept} {space} {cancel}"
				],
				'shift' : [
					"Q W E R T Y {bksp}",
					"S A M P L E {shift}",
					"{accept} {space} {cancel}"
				]
			}
		}).addTyping();

        $('.kbdicon').click(function(){
			var index = $('.kbdicon').index(this);
			$('.qwerty:eq('+index+')').getkeyboard().reveal();
		});

		// since IE adds an overlay behind the input to prevent clicking in other inputs (the keyboard may not automatically open on focus... silly IE bug)
		// We can remove the overlay (transparent) if desired using this code:
		$('.qwerty').bind('visible.keyboard', function(e, keyboard, el){
			$('.ui-keyboard-overlay').remove(); // remove overlay because clicking on it will close the keyboard... we set "openOn" to null to prevent closing.
		});
	});
	</script>
<!-- End of OnScreen Keyboard Code -->
<?php } ?>
