<?php
if(isset($settings->search) && $settings->search == 'true'){
    echo "<div class='logos f".$color." ico-search searchl'>
    <form method='get' style='display: inline-block; margin: 0px;'>
	<input class='searchi' onkeyup=\"showResult(this.value);\" tabindex='1' name='f'>
	<input type='submit' style='display: none;'>
	</form>
	<br><div id='livesearch' class=''></div>
	</div>";
}
?>
