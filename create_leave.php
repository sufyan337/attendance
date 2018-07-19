<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
<h1>Apply for leave</h1>
<form action="" method="POST">
	<table class="ui striped table">
		<tr>
			<td>Teacher name</td>
			<td><input type="text" name="teacher"></td>
		</tr>
		<tr>
			<td>Date</td>
			<td><input type="date" name="date"></td>
		</tr>
		<tr>
			<td>Purpose</td>
			<td><textarea name="purpose"></textarea></td>
		</tr>
		<tr>
			<td>How many days</td>
			<td><input type="number" name="days"></td>
		</tr>
		<td></td>
		<td><input type="submit" name="submit" value="Apply" class="ui blue button"><input type="reset" class="ui red button"></td>
	</table>
</form>

<?php

if (isset($_POST["submit"])) {
	$teacher = $_POST["teacher"];
	$purpose = $_POST["purpose"];
	global $wpdb;
	$wpdb->insert( 'wp_posts', 
					array ( 'post_title' => $teacher,
							'post_content' => $purpose,
							'post_type'	   => 'hs_leave' )
		 );
}


?>

<br><br><br><br><br><br><br><br><br><br><br><br><br>