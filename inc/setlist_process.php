<?php
include("../inc/dbcon.php");


$amount = $_POST['num'];
$db->query('truncate setlist');
for($i=1; $i<=$amount; $i++){

	if((isset($_POST['song'.$i]) && $_POST['song'.$i] != "null")){
				try{
					$id = intval($_POST['song'.$i]);
					$songs = $db ->prepare('SELECT title_visible FROM song_title WHERE id = :id');
					$songs->execute(array(':id' => $id));
					$row = $songs->fetchColumn();
					
					$delete = $db->prepare('DELETE FROM setlist WHERE id = :del');
					$delete->execute(array(':del' => $i));
					$insert = $db->prepare('INSERT INTO setlist (id, song_id) VALUES (:num,:id)');
					$insert->execute(array(':num' => $i, ':id' => $id));


				}catch (Exception $e){
					echo "couldn't select songs";
					}
		


	}elseif($_POST['song1'] == "null"){
			$delete = $db->prepare('DELETE FROM setlist WHERE id = :del');
			$delete->execute(array(':del' => $i));
	}
}

header('Location: ../');