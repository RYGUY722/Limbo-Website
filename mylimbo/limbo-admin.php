<!--The login page for admin users
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
		<?php
		# Connect to MySQL server and the database
		require( 'includes/connect_db.php' ) ;

		# Use the functions from login_tools
		require( 'includes/limbo_login_tools.php' ) ;

		if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') { #When the user submits
			$name = $_POST['name'] ; #Get the name
			$pass = $_POST['pass']; #Get the password
			$pid = validate($name,$pass) ; #Make sure it's good
			if($pid == -1) #Reload this page if it fails
				echo '<P style=color:red>Login failed, please try again.</P>' ;
			else #Load the actual page if it succeeds
				load('limbo-admin-1.php', $pid);
		}
		?>
		<h1>The following page is for admins only</h1>
		<p>If you have admin credentials, please log in below.</p>
		<!-- Get inputs from the user. -->
		<form action="limbo-admin.php" method="POST">
		<table>
		<tr>
		<td>Username</td><td><input type="text" name="name"></td>
		</tr>		
		<tr>
		<td>Password</td><td><input type="password" name="pass"></td>
		</tr>
		</table>
		<p><input type="submit" ></p>
		</form>
	</div>
</body>

</html>