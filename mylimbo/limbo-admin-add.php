<!--The login page for admin users to add new admins
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
			<?php
	require( 'includes/connect_db.php' );
	# Includes these helper functions
	require( 'includes/helpers.php' );
	
	#Only allow the user to add items if all fields are filled
	if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
		$errors = array(); #New error catcher uses an array

		if(empty($_POST['name'])) #Test if item name is invalid
			$errors[] = 'username';
		else #If not, prep name
			$name = trim($_POST['name']);
			
		if(empty($_POST['pass'])) #Test if password is invalid
			$errors[] = 'password';
		else #If not, prep password
			$pass = trim($_POST['pass']);
			
		if(empty($_POST['email'])) #Test if email is invalid
			$errors[] = 'email address';
		else #If not, prep email
			$email = trim($_POST['email']);
			
		if( !empty( $errors ) ){ #Check for errors and report them
			echo 'An error has occurred. Please check the  ' ;
			foreach ( $errors as $msg ) { echo " - $msg " ; }
		}
		else{ #Otherwise, pass the values through
			$result = add_admin($dbc, $name, $pass, $email) ;

			echo "<p>Success, thank you for submitting!</p>" ;
		}
	}

	#Close the connection
	mysqli_close($dbc);
?>
	<div class="main"> <!--main is where all the text and stuff goes... the main part of the page-->
		<p><a href="limbo-admin-1.php">Back to admin terminal</a></p>
		<h1>Add new user</h1>
		<form action="limbo-admin-add.php" method="POST">
		<table>
		<tr>
		<td>Username:</td><td><input type="text" name="name"></td>
		</tr>
		<tr>
		<td>Password:</td><td><input type="password" name="pass"></td>
		</tr>
		<tr>
		<td>Email Address:</td><td><input type="email" name="email"></td>
		</tr>
		</table>
		<p><input type="submit" ></p>
		</form>
	</div>
</body>

</html>