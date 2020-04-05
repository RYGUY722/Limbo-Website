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
					show_record_admin($dbc, $_GET['id']);}
			}
			
			else if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') { #This checks that NOT NULL values aren't, well, null
				$errors = array(); #New error catcher uses an array
				$room=$_POST['room']; #Some values don't matter if they're null
				$contact=$_POST['contact'];
				$contact2=$_POST['contact2'];
				$desc=$_POST['desc'];
				$owner=$_POST['owner'];
				$finder=$_POST['finder'];
				$status=$_POST['status']; #Others have a selection box and have to have a value
				$area=$_POST['area'];
				
				if(empty($_POST['id'])) #Test if item id is invalid
					$errors[] = 'item id';
				else #If not, prep id
					$id = trim($_POST['id']);

				if(empty($_POST['name'])) #Test if item name is invalid
					$errors[] = 'item name';
				else #If not, prep name
					$name = trim($_POST['name']);
					
				if(empty($_POST['lfdate'])) #Test if lostfound date is invalid
					$errors[] = 'lost/found date';
				else #If not, prep name
					$lfdate = trim($_POST['lfdate']);
					
				if( !empty( $errors ) ){ #Check for errors and report them
					echo 'An error has occurred. Please check the  ' ;
					foreach ( $errors as $msg ) { echo " - $msg " ; }
				}
				else{ #Otherwise, pass the values through
					$result = update_record($dbc, $_GET['id'], $room, $contact, $contact2, $desc, $owner, $finder, $status, $area, $id, $name, $lfdate) ;

					echo "<p>Record modified successfully.</p>" ;
					show_record_admin($dbc, $id);
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