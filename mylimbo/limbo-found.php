<!--The page a user can submit a new found item to the stuff table
Created by Ryan Sheffler, Vincent Acocella, and Anthony Buzzell
-->
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8"> <!--Set up the page by grabbing the stylesheet and everything-->
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
		<?php #This php is placed here specifically so that the "Success" text shows up correctly
		require( 'includes/connect_db.php' );
		# Includes these helper functions
		require( 'includes/helpers.php' );
		
		#Only allow the user to add items if all fields are filled
		if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
			$room=$_POST['room'];
			$contact2=$_POST['contact2'];
			$desc=$_POST['desc'];
			$errors = array(); #New error catcher uses an array

			if(empty($_POST['name'])) #Test if item name is invalid
				$errors[] = 'item name';
			else #If not, prep name
				$name = trim($_POST['name']);
				
			if(empty($_POST['date'])) #Test if found date is invalid
				$errors[] = 'found date';
			else #If not, prep date
				$date = trim($_POST['date']);
				
			if(empty($_POST['area'])) #Test if building/area is invalid
				$errors[] = 'building/area';
			else #If not, prep area
				$area = trim($_POST['area']);
				
			if(empty($_POST['finder'])) #Test if finder name is invalid
				$errors[] = 'your name';
			else #If not, prep owner
				$finder = trim($_POST['finder']);
				
			if(empty($_POST['contact'])) #Test if contact info is invalid
				$errors[] = 'contact info';
			else #If not, prep contact
				$contact = trim($_POST['contact']);
				
			#if(!empty($_POST['room'])
			#	$room=$_POST['room'];
			
			#if(!empty($_POST['contact2'])
			#	$contact2=$_POST['contact2'];
			
			#if(!empty($_POST['desc'])
			#	$desc=$_POST['desc'];
				
			if( !empty( $errors ) ){ #Check for errors and report them
				echo 'An error has occurred. Please check the  ' ;
				foreach ( $errors as $msg ) { echo " - $msg " ; }
			}
			else{ #Otherwise, pass the values through
				$result = insert_found_record($dbc, $name, $date, $area, $finder, $contact, $room, $contact2, $desc) ;

				echo "<p>Success, thank you for submitting!</p>" ;
			}
		}

		# We can't close the connection just yet
		?>
		<h1>Found something?</h1>
		<p>Someone probably lost it. Give us some information and we can get it back to its owner.</p>
		<form action="limbo-found.php" method="POST"> <!--Start the form for users to input information about the item they found-->
			<table>
			<tr>
			<td>Item Name:</td><td><input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"></td><td>*</td>
			</tr>
			<tr>
			<td>Approximate Date Found:</td><td><input type="date" name="date" value="<?php if (isset($_POST['date'])) echo $_POST['date']; ?>"></td><td>*</td>
			</tr>
			<tr>
			<td>Building/Area:</td> <td> <!--I streamlined the creation of this dropdown menu by creating and using a function in helpers-->
				<select name="area" >
					<option value=""selected>Choose one...</option>
					<?php get_all_locations($dbc,-1);
					#Now we can close the connection
					mysqli_close( $dbc ) ;?>
				</select>
			</td><td>*</td>
			</tr>
			<tr>
			<td>Room:</td><td><input type="text" name="room" value="<?php if (isset($_POST['room'])) echo $_POST['room']; ?>"></td>
			</tr>
			<tr>
			<td>Your Name:</td><td><input type="text" name="owner" value="<?php if (isset($_POST['owner'])) echo $_POST['owner']; ?>"></td><td>*</td>
			</tr>
			<tr>
			<td>Contact Info:</td><td><input type="text" name="contact" value="<?php if (isset($_POST['contact'])) echo $_POST['contact']; ?>"></td><td>*</td>
			</tr>
			<tr>
			<td>Secondary Contact Info:</td><td><input type="text" name="contact2" value="<?php if (isset($_POST['contact2'])) echo $_POST['contact2']; ?>"></td>
			</tr>
			<tr>
			<td>Description:</td><td><input type="textarea" name="desc" value="<?php if (isset($_POST['desc'])) echo $_POST['desc']; ?>"></td>
			</tr>
			</table>
		<p>*=Information is required.</p>
		<input type="submit" >
		</form>
	</div>
</body>

</html>