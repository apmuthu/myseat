<?php
echo "<div class='center width-70'><br/>";

	outletList($_SESSION['outletID'],'enabled','reservation_outlet_id','ON');

    $dateComponents = getdate();
	list($sy,$sm,$sd) = explode("-",$_SESSION['selectedDate']);
    echo build_calendar($sm,$sy,$dateArray);

echo"</div><br/>";
?>