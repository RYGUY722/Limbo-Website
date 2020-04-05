--This file creates the Limbo database and adds tables for admins, lost and found items, and locations.
--In addition, adds an admin and all location names.
--Author: Ryan Sheffler, Anthony Buzzell, Vincent Acocella
DROP database IF EXISTS limbo_db;
CREATE DATABASE limbo_db;
USE limbo_db;

--Create the users table, which holds all site admins
CREATE TABLE IF NOT EXISTS users(
	user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	username VARCHAR(20) NOT NULL,
	email VARCHAR(60) UNIQUE NOT NULL,
	pass CHAR(40) NOT NULL,
	reg_date DATETIME NOT NULL
);

--Create the stuff table, which will hold all information about lost and found items
CREATE TABLE stuff (
	id INT AUTO_INCREMENT PRIMARY KEY,
    item TEXT NOT NULL, 
	location_id INT NOT NULL,
	description TEXT,
	create_date DATETIME NOT NULL,
	lostfound_date DATE NOT NULL,
	update_date DATETIME,
	contact_info TEXT,
	contact_info2 TEXT,
	room TEXT,
	owner TEXT,
	finder TEXT,
	status SET('found', 'lost','claimed') NOT NULL
);

--Create the locations table, which holds the names of all buildings on campus
CREATE TABLE locations(
	id INT AUTO_INCREMENT PRIMARY KEY,
	create_date DATETIME NOT NULL,
	update_date DATETIME NOT NULL,
	name TEXT NOT NULL
);

--Add any site admins to the users table
INSERT INTO users(username,email,pass,reg_date)
VALUE('admin','someone@example.com','gaze11e',Now());

--Add all building names to the locations table
INSERT INTO locations(create_date,update_date,name)
VALUES(Now(),Now(),'Hancock Center'),
(Now(),Now(),'Champagnat Hall'),
(Now(),Now(),'Leo Hall'),
(Now(),Now(),'Lower New Townhouses' ),
(Now(),Now(),'Foy Townhouses'),
(Now(),Now(),'Lavelle Hall(Building B)'),
(Now(),Now(),'Ward Hall(Building A)'),
(Now(),Now(),'Building C'),
(Now(),Now(),'Building D'),
(Now(),Now(),'Byrne House'),
(Now(),Now(),'Cannavino Library'),
(Now(),Now(),'Chapel'),
(Now(),Now(),'Cornell Boathouse'),
(Now(),Now(),'Donnelly Hall'),
(Now(),Now(),'Dyson Center'),
(Now(),Now(),'Fern Tor'),
(Now(),Now(),'Fontaine Hall'),
(Now(),Now(),'Lower Fulton Townhouses'),
(Now(),Now(),'Upper Fulton Townhouses'),
(Now(),Now(),'Greystone Hall'),
(Now(),Now(),'Kieran Gatehouse'),
(Now(),Now(),'Kirk House'),
(Now(),Now(),'Lowell Thomas'),
(Now(),Now(),'Marian Hall'),
(Now(),Now(),'Marist Boathouse'),
(Now(),Now(),'McCann Recreational Center'),
(Now(),Now(),'Mid-Rise Hall'),
(Now(),Now(),'St. Ann\'s Hermitage'),
(Now(),Now(),'St. Peter\'s'),
(Now(),Now(),'Sheahan Hall'),
(Now(),Now(),'Science and Allied Health Building'),
(Now(),Now(),'Steel Plant Studios and Gallery'),
(Now(),Now(),'Student Center'),
(Now(),Now(),'Upper West Townhouses'),
(Now(),Now(),'Lower West Townhouses');

--Add some demo data to the stuff table
INSERT INTO stuff(item,location_id, description, create_date, lostfound_date, contact_info, room,owner, finder, STATUS)
VALUES 
("Marist ID", 1, "I left my Marist ID on the table by the fireplace.", '2018-11-05 20:14:07', '2018-11-06 10:56:02', '(xxx-)xxx-xxxx', "Lobby", "Bob Fox", "", 'lost'),

("Communications Textbook", 4, "I left my book in a lower New townhouse when working on a project but can not remember what house.", '2018-10-29 16:43:01', '2018-11-02 22:13:09', '(xxx-)xxx-xxxx', "", "Sarah Cap", "No Finder", 'lost'),

("Iphone Headsets", 9, "I was eating in the Building D dining hall and I forgot to bring my earphones along with me", '2018-11-02 14:23:10', '2018-11-03 22:45:02', '(xxx-)xxx-xxxx', "main dining area", "Paul Blart", "", 'lost'),

("Marist Water bottle", 11 , "I found a Marist water bottle in my class that does not belong to anyone here currently.", '2018-11-01 14:12:10' ,'2018-11-04 09:10:45', '(xxx-)xxx-xxxx', "2nd floor study room 3016", "", "Steve Smith", 'found'),

("Room Keys", 14 , "A set of room keys were found on the floor in Donnelly Hall.", '2018-11-09 23:09:45', '2018-11-10 20:28:11', '(xxx-)xxx-xxxx', "Main Hallway", "", "Billy Bob", 'found'),

("USB Drive" , 17 , "A USB with a name on it was found in the bathroom of Fountain Hall.", '2018-11-01 09:15:06', '2018-11-02 10:43:22', '(xxx-)xxx-xxxx', "Bathroom", "Michael Booster", "Stacy Moon", 'found');

select * FROM stuff;



