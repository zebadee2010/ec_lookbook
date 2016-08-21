<?php
include("../inc/dbcon.php");
if (!isset($_POST['name'])){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    echo "Please fill out all fields.";
}

//Get data from _POST var and escape where possible
//song title
$title_space=trim(ucwords(strtolower($_POST['name'])));
$title_no_space=str_replace(' ', '', $title_space);

//color values
$red=$_POST['red']; 
$green=$_POST['green']; 
$blue=$_POST['blue']; 
$description=$_POST['color'];

//select menu values
$time=intval($_POST['timing_select']);
$cue=intval($_POST['cue_select']);

//dmx vs percent toggle
$toggle = $_POST['toggle'];

$datetime = date('Y-m-d H:i:s');

//Add song in database
//Make sure song_title is unique. If not redirect and show error
try{
    $stmt = $db->prepare('INSERT INTO song_title (title_visible,title_hidden,date_created,date_updated) VALUES (:space , :nospace, :create , :update)');
    $stmt->execute(array(':space' => $title_space, ':nospace' => $title_no_space, ':create' => $datetime, ':update' => $datetime));
    
    //For use in song_color query
    $lastsong = $db->lastInsertID();
	
}catch (Exception $e){
    echo "Duplicate song_title, please edit this song instead.";
}
			


//Get length of array to loop through
$count=count($red);

//If Percent radio button selected
if ($toggle=="percent"){
	
	//Loop through length of color array and post to database
    for($i=0;$i<$count;$i++) {
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
        $song->bindValue(':song', $lastsong, PDO::PARAM_INT);
        $song->bindValue(':color', $last_color, PDO::PARAM_INT);
        $song->execute();
 
    }
}elseif ($toggle=="dmx"){  //If dmx radio button selected
	
//Loop through length of color array and post to database
    for($i=0;$i<$count;$i++) {
		//Set color variables per loop
        $red[$i]=intval($red[$i]);
        $green[$i]=intval($green[$i]);
        $blue[$i]=intval( $blue[$i]);
        
		//Convert values for storing in percent table
        $red_percent[$i]=(($red[$i] *100 )/255);
        $green_percent[$i]=(($green[$i] *100 )/255);
        $blue_percent[$i]=(($blue[$i] *100 )/255);
        
      //Insert values into Color table
        $color = $db->prepare("INSERT INTO color (red_dmx,green_dmx,blue_dmx,red_percent,green_percent,blue_percent,description) 
                                VALUES (:red_d,:green_d,:blue_d,:red_p,:green_p,:blue_p,:description)");
        $color->bindValue(':red_d', $red[$i], PDO::PARAM_INT);
        $color->bindValue(':green_d', $green[$i], PDO::PARAM_INT);
        $color->bindValue(':blue_d', $blue[$i], PDO::PARAM_INT);
		$color->bindValue(':red_p', $red_dmx[$i], PDO::PARAM_INT);
        $color->bindValue(':green_p', $green_dmx[$i], PDO::PARAM_INT);
        $color->bindValue(':blue_p', $blue_dmx[$i], PDO::PARAM_INT);
		$color->bindValue(':description', trim(ucfirst(strtolower($description[$i]))), PDO::PARAM_STR);
        $color->execute();

        
		//Pull RowID for use in song_color relationship table
        $last_color = $db->lastInsertID();
        
		//Insert row into song_color relationship table
        $song = $db->prepare("INSERT INTO song_color (song_id, color_id) VALUES (:song,:color)");
        $song->bindValue(':song', $lastsong, PDO::PARAM_INT);
        $song->bindValue(':color', $last_color, PDO::PARAM_INT);
        $song->execute();
        
    }
}else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        echo "Please select the type of color value.";
    }

//Insert into song_parameters table
$param = $db->prepare("INSERT INTO song_parameters (song_id, fire_cue, time_signature)
                        VALUES (:song, :cue, :time)");
$param->bindValue(':song', $lastsong, PDO::PARAM_STR);
$param->bindValue(':cue', $cue, PDO::PARAM_STR);
$param->bindValue(':time', $time, PDO::PARAM_STR);
$param->execute();

//Set image parameters for file upload (size, type, and location)
$imgformats = array("jpg", "png", "jpeg", "tif", "tiff", "JPG", "PNG", "JPEG", "TIFF", "TIF");
$max_file_size = 1024 * 10000;
$storage_path = "../uploads/images";
$count = 0;


//If image is set, then process
/*
Include image class

include("imgprocess.php");

//Initialise / load image
$imgobj = new resize('sample.jpg');

//Resize image
$imgobj -> resizeImage(150,100,'crop');

//Save Image
$imgobj -> saveImage('newname.jpg', 100);

*/  //custom image resize

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
                   if(move_uploaded_file($_FILES['img']["tmp_name"][$f], $storage_path.$title_no_space.$count.$extension)){
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
                   $relationship -> bindParam(':song', $lastsong);
                   $relationship -> bindParam(':img', $lastimg);
                   $relationship -> execute();
		}//End If
               }//End else
        }//End if (files error)
    }//End for each
}//End if(isset)

header('Location: ' . $_SERVER['HTTP_REFERER']);

