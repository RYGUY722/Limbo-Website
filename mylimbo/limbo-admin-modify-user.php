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
		<p><a href="limbo-admin-table-user.php">Back to table</a></p>
		<?php
			require( 'includes/connect_db.php' );
			# Includes these helper functions
			require( 'includes/helpers.php' );
			
			# Show the item information based on the id
			if($_SERVER['REQUEST_METHOD'] == 'GET'){
				if(isset($_GET['id'])){
					show_record_user($dbc, $_GET['id']);}
			}
			
			else if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') { #This checks that NOT NULL values aren't, well, null
				

				if(empty($_POST['name'])) #Test if username is invalid
					$errors[] = 'username';
				else #If not, prep name
					$name = trim($_POST['name']);

				if(empty($_POST['pass'])) #Test if item id is invalid
					$errors[] = 'password';
				else #If not, prep id
					$pass = trim($_POST['pass']);

				if(empty($_POST['email'])) #Test if email is invalid
					$errors[] = 'email';
				else #If not, prep name
					$email = trim($_POST['email']);
					
				if( !empty( $errors ) ){ #Check for errors and report them
					echo 'An error has occurred. Please check the  ' ;
					foreach ( $errors as $msg ) { echo " - $msg " ; }
				}
				else{ #Otherwise, pass the values through
					$result = update_record_user($dbc, $_GET['id'], $name, $pass, $email) ;

					echo "<p>User modified successfully.</p>" ;
					show_record_user($dbc, $_GET['id']);
				}
			}
			else #In the strange case where a user accesses limbo-admin-modify.php directly without any item id, just print an error.
				echo '<h1>An error has occurred. Please go back to the <a href="limbo-admin-table.php">table</a> and select a record.</h1>';

			# Close the connection
			mysqli_close( $dbc ) ;
		?>
	</div>
</body>

</html>