<?php
include("../inc/dbcon.php");

$datetime = date('Y-m-d H:i:s');
$song_id = intval($_POST['unique']);
$title_visible = $db->prepare("SELECT title_visible FROM song_title WHERE id = :id");
$title_visible->execute(array(':id' => $song_id));
$title_space = $title_visible->fetchColumn();
$title_no_space=str_replace(' ', '', $title_space);


if(isset($_POST['u_title']) && isset($_POST['title'])){
	$stmt = $db->prepare("UPDATE song_title SET title_visible=:newtitle, title_hidden=:newhidden, date_updated=:update WHERE id=:id");
	$stmt->execute(array(':newtitle' => $title_space, ':newhidden' => $title_no_space, ':update' => $datetime, ':id' => $_POST['u_title']));
}

if(isset($_POST['image'])){
	$image=$_POST['image'];
	foreach($image as $img){
            $url = $db->prepare("SELECT src FROM img WHERE id=:image");
            $url->execute(array(':image' => intval($img)));
            $src = $url->fetchColumn();
            unlink($src);
			$stmt = $db->prepare("DELETE FROM img WHERE id=:image");
			$stmt->execute(array(':image' => intval($img)));	
		
	}
	
}


if(isset($_POST['delcolor'])){
	$delcolor=$_POST['delcolor'];
	foreach($delcolor as $color){
			$stmt = $db->prepare("DELETE FROM color WHERE id=:color");
			$stmt->execute(array(':color' => intval($color)));
		}
	}
	




if(isset($_POST['color'])){
	
$red=$_POST['red']; 
$green=$_POST['green']; 
$blue=$_POST['blue']; 
$description=$_POST['color'];
$count=count($red);
	

    for($i=1;$i<=$count;$i++) {
		//Set color variables per loop
        $red[$i]=intval($red[$i]);
        $green[$i]=intval($green[$i]);
        $blue[$i]=intval( $blue[$i]); 

		
		
        //Convert values for storing in dmx table
        $red_dmx[$i]=(($red[$i] *255 )/100);
        $green_dmx[$i]=(($green[$i] *255 )/100);
        $blue_dmx[$i]=(($blue[$i] *255 )/100);
        
        //Insert values into Color table
        $color = $db->prepare("INSERT INTO color (red_percent,green_percent,blue_percent,red_dmx,green_dmx,blue_dmx,description) 
                                VALUES (:red_p,:green_p,:blue_p,:red_d,:green_d,:blue_d,:description)");
        $color->bindValue(':red_p', $red[$i], PDO::PARAM_INT);
        $color->bindValue(':green_p', $green[$i], PDO::PARAM_INT);
        $color->bindValue(':blue_p', $blue[$i], PDO::PARAM_INT);
		$color->bindValue(':red_d', $red_dmx[$i], PDO::PARAM_INT);
        $color->bindValue(':green_d', $green_dmx[$i], PDO::PARAM_INT);
        $color->bindValue(':blue_d', $blue_dmx[$i], PDO::PARAM_INT);
		$color->bindValue(':description', trim(ucfirst(strtolower($description[$i]))), PDO::PARAM_STR);
        $color->execute();
       		
        //Pull RowID for use in song_color relationship table
        $last_color = $db->lastInsertID();
        
		//Insert row into song_color relationship table
		
        $song = $db->prepare("INSERT INTO song_color (song_id, color_id) VALUES (:song,:color)");
        $song->bindValue(':song', $song_id, PDO::PARAM_INT);
        $song->bindValue(':color', $last_color, PDO::PARAM_INT);
        $song->execute();
 
    }
}

$imgformats = array("jpg", "png", "jpeg", "tif", "tiff", "JPG", "PNG", "JPEG", "TIFF", "TIF");
$max_file_size = 1024 * 5000;
$storage_path = "../uploads/images/";
$count = 1;

if(isset($_FILES['img']) and $_SERVER['REQUEST_METHOD'] == "POST"){
	//Loop through images using $name as a var for the image path
    foreach($_FILES['img']['name'] as $f => $name){
		//Remove image/ from file extension and replace with '.' and the file extension
        $extension = str_replace('image/', '.', $_FILES['img']['type'][$f]);
        
		
        if ($_FILES['img']['error'][$f] == 4){
            continue;
        }//End if
        if ($_FILES['img']['error'][$f] == 0){                  //IF FILE HAS NO ERRORS
            if($_FILES['img']['size'][$f] > $max_file_size){	//IF FILE IS NOT TO BIG
                $message[] = "$name is too large!";
                continue;
            }//End if
               elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $imgformats)){ //IF FILE IS ACCEPTABLE FORMAT
                   $message[] = "$name is not a valid format";
                   continue;
               }//End elseif
               else{
				   
				   //If none of the above errors, then move file to new location storing the path, count var, and extenstion
                   if(move_uploaded_file($_FILES['img']["tmp_name"][$f], $storage_path.$title_no_space.$count.$extension))
                       $file_path = $storage_path.$title_no_space.$count.$extension;
                       $count++;
                   
                   //Insert File path into database
                   $insert = $db->prepare('INSERT INTO img (src) VALUES (:file_path)');
                   $insert -> bindParam(':file_path', $file_path);
                   $insert -> execute();
                   
				   //Pull RowID for use in song_img relationship table
                   $lastimg = $db->lastInsertId();
                   
				   //Insert into relationship table
                   $relationship = $db -> prepare('INSERT INTO song_img (song_id, img_id) VALUES (:song, :img)');
                   $relationship -> bindParam(':song', $song_id);
                   $relationship -> bindParam(':img', $lastimg);
                   $relationship -> execute();
               }//End else
        }//End if (files error)
    }//End for each
}//End if(isset)

header('Location: ' . $_SERVER['HTTP_REFERER']);