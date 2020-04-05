<?php
#Modified by Ryan Sheffler 11/12/2018
$debug = false;

		#STANDARD FUNCTIONS


#SUPPORTING FUNCTIONS


# Shows the query as a debugging aid
function show_query($query) {
  global $debug;

  if($debug)
    echo "<p>Query = $query</p>" ;
}

# Checks the query results as a debugging aid
function check_results($results) {
  global $dbc;

  if($results != true)
    echo '<p>SQL ERROR = ' . mysqli_error( $dbc ) . '</p>'  ;
}


#DISPLAY FUNCTIONS


# Shows the records within the last few days
function show_records_home($dbc, $days) {
	# Create a query to get the date item was lost/found, reported date, item status, item name, and id sorted by reported date
	$query = 'SELECT lostfound_date,create_date, status, item, id FROM stuff WHERE DATEDIFF(CURDATE(),create_date)<'.$days.' ORDER BY create_date DESC' ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
  		echo '<TABLE>';
  		echo '<TR>';
  		echo '<TH>Date/time</TH>';
  		echo '<TH>Status</TH>';
		echo '<TH>Stuff</TH>';
  		echo '</TR>';

  		# For each row result, generate a table row
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
    		echo '<TR>' ;
    		echo '<TD>' . $row['lostfound_date'] . '</TD>' ;
    		echo '<TD>' . $row['status'] . '</TD>' ;
			$alink = '<A HREF=limbo-ql.php?id=' . $row['id'] . '>' . $row['item'] . '</A>'; #Make the row a link which users can click to see the rest of the details.
			echo '<TD>' . $alink .'</TD>'; #Create a row of the table using that link    		
			echo '</TR>' ;
  		}

  		# End the table
  		echo '</TABLE>';

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

function show_record($dbc, $id) { #A modified version of show_records, changed to show details of only one row
	# Create a query to get the almost all information of an item from stuff
	$query = 'SELECT item,lostfound_date,create_date,update_date,location_id,owner,finder,contact_info,room,contact_info2,description,status FROM stuff WHERE id= ' . $id ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before

  		# For each row result, generate a table row
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
			echo '<H1>'.$row['item'].'</H1>' ;
			if($row['status']=='lost'){
				echo '<H2>Item is currently lost</H2>' ;
			}
			else if($row['status']=='found'){
				echo '<H2>Item is currently lost</H2>' ;
			}
			else{
				echo '<H2>Item has been claimed by '.$row['owner'].'.</H2>' ;
			}
			echo '<TABLE>';
    		echo '<TR><TD>Description</TD><TD>' . $row['description'] . '</TD></TR>' ;
			$loc=mysqli_fetch_array(mysqli_query($dbc,'SELECT name FROM locations WHERE id='.$row['location_id']));
			if($row['status']=='found'){
				echo '<TR><TD>Found on</TD><TD>' . $row['lostfound_date'] . '</TD></TR>' ;
				echo '<TR><TD>Found by</TD><TD>' . $row['finder'] . '</TD></TR>' ;
				echo '<TR><TD>Lost at</TD><TD>' . $loc['name'] . '</TD></TR>' ;
				if(!empty($row['room']))
					echo '<TR><TD>Found in</TD><TD>' . $row['room'] . '</TD></TR>' ;
			}
			else{
				echo '<TR><TD>Lost on</TD><TD>' . $row['lostfound_date'] . '</TD></TR>' ;
				echo '<TR><TD>Lost by</TD><TD>' . $row['owner'] . '</TD></TR>' ;
				echo '<TR><TD>Lost at</TD><TD>' . $loc['name'] . '</TD></TR>' ;
				if(!empty($row['room']))
					echo '<TR><TD>Lost in</TD><TD>' . $row['room'] . '</TD></TR>' ;
				if(!empty($row['finder']))
					echo '<TR><TD>Found by</TD><TD>' . $row['finder'] . '</TD></TR>' ;
			}    		
    		echo '<TR><TD>Reported on</TD><TD>' . $row['create_date'] . '</TD></TR>' ;
    		echo '<TR><TD>Contact Line</TD><TD>' . $row['contact_info'] . '</TD></TR>' ;
			if(!empty($row['contact2']))
				echo '<TR><TD>Contact Line 2</TD><TD>' . $row['contact2'] . '</TD></TR>' ;
			
			#End the table
			echo '</TABLE>';
			
			#If it's unclaimed, allow the user to claim it
			if($row['status']=='claimed'){
				echo '<p> Item was claimed on '.$row['update_date'].' by '.$row['owner'].'.</p>';
			}
			else{
				echo '<p> Item is unclaimed. Enter your name below to claim the item.</p>';
				echo '<form action="limbo-ql.php?id='.$id.'" method="POST">';
				echo '<table><tr><td>Your Name</td><td><input type="text" name="name"></td></tr></table>';
				echo '<input type="submit" value="Claim"></form>';
			}
  		}

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

function get_all_locations($dbc,$selectedid) {
	# Create a query to get the date item was lost/found, reported date, item status, item name, and id sorted by reported date
	$query = 'SELECT name, id FROM locations ORDER BY id ASC' ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# For each row result, output a selection box choice
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
			if($row['id']==$selectedid)
				echo '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
			else
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  		}

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}


#MODIFICATION FUNCTIONS


# Inserts a record into the stuff table with an owner 
function insert_lost_record($dbc, $name, $date, $area, $owner, $contact, $room, $contact2, $desc) {
  $query = 'INSERT INTO stuff(item,lostfound_date,create_date,location_id,owner,contact_info,room,contact_info2,description,status)
  VALUES ("'.$name.'","'.$date.'",Now(),"'.$area.'","'.$owner.'","'.$contact.'","'.$room.'","'.$contact2.'","'.$desc.'","lost")' ; #Adds all the values into the table
  show_query($query);

  $results = mysqli_query($dbc,$query) ;
  check_results($results) ;

  return $results ;
}

# Inserts a record into the stuff table with a finder
function insert_found_record($dbc, $name, $date, $area, $finder, $contact, $room, $contact2, $desc) {
  $query = 'INSERT INTO stuff(item,lostfound_date,create_date,location_id,finder,contact_info,room,contact_info2,description,status)
  VALUES ("'.$name.'","'.$date.'",Now(),"'.$area.'","'.$finder.'","'.$contact.'","'.$room.'","'.$contact2.'","'.$desc.'","found")' ; #Adds all the values into the table
  show_query($query);

  $results = mysqli_query($dbc,$query) ;
  check_results($results) ;

  return $results ;
}

function claim_record($dbc,$name,$id){
	# Create a query to get the status for the id
	$query = 'SELECT status FROM stuff WHERE id='.$id ;
	show_query($query);

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# For each row result, output a selection box choice
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
    		if($row['status']=='lost'){
				#Make a query to insert the name and update date
				$query = 'UPDATE stuff SET owner=\''.$name.'\',update_date=Now(),status=\'claimed\' WHERE id='.$id ;
				show_query($query);
				# Execute the query
				mysqli_query( $dbc , $query ) ;
			}
			if($row['status']=='found'){
				#Make a query to insert the name and update date
				$query = 'UPDATE stuff SET finder=\''.$name.'\',update_date=Now(),status=\'claimed\' WHERE id='.$id ;
				show_query($query);
				# Execute the query
				mysqli_query( $dbc , $query ) ;
			}
  		}

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}


		#ADMIN FUNCTIONS

#MODIFICATION FUNCTIONS


#Adds a new admin to the users table
function add_admin($dbc,$name,$pass,$email){
	#Make a query to add the new user to the users table
	$query = 'INSERT INTO users(username,pass,email,reg_date) VALUE(\''.$name.'\',\''.$pass.'\',\''.$email.'\',Now())';
	
	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;
}

function delete_record($dbc,$id){
	#Make a query to delete a row of stuff
	$query = 'DELETE FROM stuff WHERE id='.$id;
	show_query($query);
	
	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;
}

function update_record($dbc, $id, $room, $contact, $contact2, $desc, $owner, $finder, $status, $area, $newid, $name, $lfdate){
	#Make a query to modify a row of stuff
	$query = 'UPDATE stuff
	SET id='.$newid.',
    item="'.$name.'", 
	location_id='.$area.',
	description="'.$desc.'",
	lostfound_date=\''.$lfdate.'\',
	update_date=Now(),
	contact_info="'.$contact.'",
	contact_info2="'.$contact2.'",
	room="'.$room.'",
	owner="'.$owner.'",
	finder="'.$finder.'",
	status=\''.$status.'\'
	WHERE id='.$id;
	show_query($query);
	
	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;
}

function delete_record_user($dbc,$id){
	#Make a query to delete a row of users
	$query = 'DELETE FROM users WHERE user_id='.$id;
	show_query($query);
	
	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;
}

function update_record_user($dbc, $id, $name, $pass, $email){
	#Make a query to modify a row of stuff
	$query = 'UPDATE users
	SET username="'.$name.'", 
	pass="'.$pass.'",
	email="'.$email.'"
	WHERE user_id='.$id;
	show_query($query);
	
	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;
}


#DISPLAY FUNCTIONS


#Displays a table with data from stuff formatted for admins with links to delete and modify data
function show_records_admin($dbc) {
	# Create a query to get the date item was lost/found, reported date, item status, item name, and id sorted by reported date
	$query = 'SELECT lostfound_date,create_date, update_date, status, item, id FROM stuff ORDER BY create_date DESC' ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
  		echo '<TABLE>';
  		echo '<TR>';
  		echo '<TH>Submission ID</TH>';
  		echo '<TH>Item</TH>';
		echo '<TH>Lost/found date</TH>';
		echo '<TH>Submission date</TH>';
		echo '<TH>Last update</TH>';
		echo '<TH>Status</TH>';
		echo '<TH></TH>';
		echo '<TH></TH>';
  		echo '</TR>';

  		# For each row result, generate a table row
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
    		echo '<TR>' ;
			echo '<TD>' . $row['id'] . '</TD>' ;
			echo '<TD>' . $row['item'] . '</TD>' ;
    		echo '<TD>' . $row['lostfound_date'] . '</TD>' ;
    		echo '<TD>' . $row['create_date'] . '</TD>' ;
    		echo '<TD>' . $row['update_date'] . '</TD>' ;
    		echo '<TD>' . $row['status'] . '</TD>' ;
			echo '<TD><A HREF=limbo-admin-modify.php?id=' . $row['id'] . '>Modify</A></TD>'; #Create a row of the table using a link so admins can modify records
			echo '<TD><A HREF=limbo-admin-delete.php?id=' . $row['id'] . '>Delete</A></TD>'; #Create a row of the table using a link so admins can delete records			
			echo '</TR>' ;
  		}

  		# End the table
  		echo '</TABLE>';

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

#Displays one row of the table in text box form so an admin can modify it to their liking
function show_record_admin($dbc,$id) {
	# Create a query to get the date item was lost/found, reported date, item status, item name, and id sorted by reported date
	$query = 'SELECT * FROM stuff WHERE id='.$id ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
  		echo '<form action="limbo-admin-modify.php?id='.$id.'" method="POST">';
		echo '<TABLE>';

  		# For each row result, generate a table row
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
			echo '<TR><TD>Submission ID</TD><TD><input type="number" name="id" value="' . $row['id'] . '"></TD></TR>' ;
			echo '<TR><TD>Item name</TD><TD><input type="text" name="name" value="' . $row['item'] . '"></TD>' ;
			
			echo '<TR><TD>Location ID</TD><TD><select name="area">'; #Location has specific choices, so a selection box is used
			get_all_locations($dbc,$row['location_id']);
			echo '</select></TD></TR>' ; 
			
			echo '<TR><TD>Description</TD><TD><input type="textarea" name="desc" value="' . $row['description'] . '"></TD></TR>' ;
    		echo '<TR><TD>Date lost/found</TD><TD><input type="date" name="lfdate" value="' . $row['lostfound_date'] . '"></TD>' ;
			
    		echo '<TR><TD>Submission date</TD><TD>' . $row['create_date'] . '</TD>' ; #These two can't be modified.
    		echo '<TR><TD>Last update</TD><TD>' . $row['update_date'] . '</TD>' ;
			
			echo '<TR><TD>Contact line 1</TD><TD><input type="text" name="contact" value="' . $row['contact_info'] . '"></TD></TR>' ;
			echo '<TR><TD>Contact line 2</TD><TD><input type="text" name="contact2" value="' . $row['contact_info2'] . '"></TD></TR>' ;
			echo '<TR><TD>Room lost/found</TD><TD><input type="text" name="room" value="' . $row['room'] . '"></TD></TR>' ;
			echo '<TR><TD>Owner name</TD><TD><input type="text" name="owner" value="' . $row['owner'] . '"></TD></TR>' ;
			echo '<TR><TD>Finder name</TD><TD><input type="text" name="finder" value="' . $row['finder'] . '"></TD></TR>' ;
			
    		echo '<TR><TD>Item status</TD><TD><select name="status" value="' . $row['status'] . '">'; #Selection box since there are only 3 possiblities for status
			if($row['status']=='lost')
				echo '<option value="lost" selected>lost</option>';
			else
				echo '<option value="lost">lost</option>';
			if($row['status']=='found')
				echo '<option value="found" selected>found</option>';
			else
				echo '<option value="found">found</option>';
			if($row['status']=='claimed')
				echo '<option value="claimed" selected>claimed</option>';
			else
				echo '<option value="claimed">claimed</option>
			</select></TD>' ;
			
  		}

  		# End the table
  		echo '</TABLE>';
		echo '<input type="submit" value="Apply changes">
		</form>';
  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}
	
	#Displays a table with data from stuff formatted for admins with links to delete and modify data
function show_records_user($dbc) {
	# Create a query to get the date item was lost/found, reported date, item status, item name, and id sorted by reported date
	$query = 'SELECT * FROM users ORDER BY user_id ASC' ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
  		echo '<TABLE>';
  		echo '<TR>';
  		echo '<TH>User ID</TH>';
  		echo '<TH>Username</TH>';
		echo '<TH>Email</TH>';
		echo '<TH>Registration Date</TH>';
  		echo '</TR>';

  		# For each row result, generate a table row
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
    		echo '<TR>' ;
			echo '<TD>' . $row['user_id'] . '</TD>' ;
			echo '<TD>' . $row['username'] . '</TD>' ;
    		echo '<TD>' . $row['email'] . '</TD>' ;
    		echo '<TD>' . $row['reg_date'] . '</TD>' ;
			echo '<TD><A HREF=limbo-admin-modify-user.php?id=' . $row['user_id'] . '>Modify</A></TD>'; #Create a row of the table using a link so admins can modify user info
			echo '<TD><A HREF=limbo-admin-delete-user.php?id=' . $row['user_id'] . '>Delete</A></TD>'; #Create a row of the table using a link so admins can delete user info			
			echo '</TR>' ;
  		}

  		# End the table
  		echo '</TABLE>';

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

#Displays one row of the table in text box form so an admin can modify it to their liking
function show_record_user($dbc,$id) {
	# Create a query to get the date item was lost/found, reported date, item status, item name, and id sorted by reported date
	$query = 'SELECT * FROM users WHERE user_id='.$id ;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
  		echo '<form action="limbo-admin-modify-user.php?id='.$id.'" method="POST">';
		echo '<TABLE>';

  		# For each row result, generate a table row
  		while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
  		{
    		echo '<TR><TD>User ID</TD><TD>' . $row['user_id'] . '</TD>' ; #These two can't be modified.
    		echo '<TR><TD>Registration date</TD><TD>' . $row['reg_date'] . '</TD>' ;
			
			echo '<TR><TD>Username</TD><TD><input type="text" name="name" value="' . $row['username'] . '"></TD></TR>' ;
			echo '<TR><TD>Password</TD><TD><input type="password" name="pass" value="' . $row['pass'] . '"></TD></TR>' ;
			echo '<TR><TD>Email</TD><TD><input type="email" name="email" value="' . $row['email'] . '"></TD></TR>' ;			
  		}

  		# End the table
  		echo '</TABLE>';
		echo '<input type="submit" value="Apply changes">
		</form>';
  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}
?>