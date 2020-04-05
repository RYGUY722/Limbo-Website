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
		<p><a href="limbo-admin-1.php">Back to main page</a></p>
		<h1>Lost and Found Table</h1>
		<?php
	require( 'includes/connect_db.php' );
	# Includes these helper functions
	require( 'includes/helpers.php' );
	
	# Show the contents of the table
	show_records_user($dbc);

	# Close the connection
	mysqli_close( $dbc ) ;
?>
	</div>
</body>

</html>