<!--The first page a user should see upon entering the limbo site
Created by Ryan Sheffler, Vincent Acocella, and Anthony Buzzell
-->
<!DOCTYPE html>
<html>
<head> <!--Set up the page by grabbing the stylesheet and everything-->
		<meta charset="utf-8">
		<title>Limbo</title>
		<link rel="stylesheet" type="text/css" href="limbo.css">
</head>
<body>
	<div class="nav"> <!--Make the ever-present navigation bar at the top of the screen-->
		<table>
			<tr>
				<td><a href="limbo-landing.php"><img src="fox.png" title="Back to Homepage" height="80" width="80"></a></td>
				<td><a href="limbo-lost.php">Lost Something?</a></td>
				<td><a href="limbo-found.php">Found Something?</a></td>
				<td><a href="limbo-admin.php">Admins</a></td>
			</tr>
		</table>
	</div>
	<div class="main"> <!--main is where all the text and stuff goes... the main part of the page-->
		<h1>Welcome to Limbo!</h1>
		<p>If you've lost or found something, you're in luck: this is the place to report it.</p>
		<h2>Reported in the last </h2>
		<form action="limbo-landing.php" method="POST">
		<select name="dayform" > <!--Create a dropdown menu to choose how long ago you want results from-->
			<option value="7">7 days</option>
			<option value="14">14 days</option>
			<option value="30">30 days</option>
			<option value="99999999">All time</option>
		</select>
		<input type="submit" >
		</form>
		<?php
	require( 'includes/connect_db.php' );
	# Includes these helper functions
	require( 'includes/helpers.php' );
	
	# Show the records
	if($_SERVER['REQUEST_METHOD'] == 'POST') #If the user switched the oldest date, use that
		show_records_home($dbc, $_POST['dayform']);
	else #Otherwise, default to 7 days or 1 week
		show_records_home($dbc,7);

	# Close the connection
	mysqli_close( $dbc ) ;
?>
	</div>
</body>

</html>