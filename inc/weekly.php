<?php 
include("../inc/dbcon.php");

$get_seg = $db->query("SELECT id, segment_name FROM weekly_segments");

while($seg = $get_seg->fetch(PDO::FETCH_ASSOC)){
		if(isset($_POST[$seg['id']])){
			$text = $_POST[$seg['segment_name']];
			$insert = $db->prepare("UPDATE weekly_segments SET notes = :text WHERE id = ".$seg["id"]);
			$insert->execute(array(":text" => $text));
		}
	}

if (isset($_POST["segment"]) && isset($_POST["note"])){
	$count = count($_POST["segment"]);
	
	for($i=1;$i<$count;$i++){
			$segments[$i] = $_POST["segment[$i]"];
				 print_r($segments[$i]);

			$segments[$i] = filter_var($segments[$i], FILTER_SANITIZE_STRING);
		 print_r($segments[$i]);
			//$notes[$i] = $_POST["note"];
	}
	
//	foreach($segments as $seg){
//		$insert = $db->prepare("INSERT INTO weekly_segments (segment_name,notes) VALUES (:segment, :notes)");
//		$insert->execute(array(":segment" =>$))
//	}
}

//if(isset($_POST['segment']) && isset($_POST['title'])){	$segment=$_POST['segment'];
//	foreach($segment as $seg){
//            $url = $db->prepare("SELECT src FROM img WHERE id=:image");
//            $url->execute(array(':image' => intval($img)));
//            $src = $url->fetchColumn();
//            unlink($src);
//			$stmt = $db->prepare("DELETE FROM img WHERE id=:image");
//			$stmt->execute(array(':image' => intval($img)));	
//		
//	}
//	
//}
//
//
//
//
//if(isset($_POST['u_title']) && isset($_POST['title'])){
//	$stmt = $db->prepare("UPDATE song_title SET title_visible=:newtitle, title_hidden=:newhidden, date_updated=:update WHERE id=:id");
//	$stmt->execute(array(':newtitle' => $title_space, ':newhidden' => $title_no_space, ':update' => $datetime, ':id' => $_POST['u_title']));
//}
//header('Location: ' . $_SERVER['HTTP_REFERER']);