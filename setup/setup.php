<?php
include("../inc/dbcon.php");

try{
    
$db->query("INSERT INTO `fire_cue` (`id`, `fire_cue`)
VALUES
	(1, 'First Verse'),
	(2, 'First Chorus'),
	(3, 'Big Intro'),
	(4, '1(FIRE),2, 1,2,3,4'),
	(5, '1,2, FIRE,2,3,4')"
		   );

$db->query("INSERT INTO `time_signature` (`id`, `time_signature`)
VALUES
	(1, '4 count'),
	(2, '6 count'),
	(3, '3 count'),
	(4, '5 count')"
          );
	
	
	echo "Everything seems to be setup properly now.";
}catch(Exception $e){
	echo "Something went wrong while adding data to the tables.";
}