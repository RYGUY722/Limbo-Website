<!--The details page for an item. This is shown when a user clicks on an item.
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
		<p><a href="limbo-admin-table.php">Back to table</a></p>
		<?php
			require( 'includes/connect_db.php' );
			# Includes these helper functions
			require( 'includes/helpers.php' );
			
			# Show the item information based on the id
			if($_SERVER['REQUEST_METHOD'] == 'GET'){
				if(isset($_GET['id'])){
					delete_record($dbc, $_GET['id']);
					echo '<p>Record has been deleted.</p>'; 
					
					#The following is basically just the load function from the login tools
					  # Begin URL with protocol, domain, and current directory.
					  $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;

					  # Remove trailing slashes then append page name to URL and the print id.
					  $url = rtrim( $url, '/\\' ) ;
					  $url .= '/limbo-admin-table.php';

					  # Execute redirect then quit.
					  session_start( );

					  header( "Location: $url" ) ;

					  exit() ;
				}
			}
			else #In the strange case where a user accesses limbo-admin-delete.php directly without any item id, just print an error.
				echo '<h1>An error has occurred. Please go back to the <a href="limbo-admin-table.php">table</a> and select a record.</h1>';

			# Close the connection
			mysqli_close( $dbc ) ;
		?>
	</div>
</body>

</html>