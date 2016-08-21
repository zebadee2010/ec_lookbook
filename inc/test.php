<?php
include("../inc/dbcon.php");


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
            if($_FILES['img']['size'][$f] > $max_file_size){    //IF FILE IS NOT TO BIG
                $message[] = "$name is too large!";
                continue;
            }//End if
               elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $imgformats)){ //IF FILE IS ACCEPTABLE FORMAT
                   $message[] = "$name is not a valid format";
                   continue;
               }//End elseif
               else{

                                   //If none of the above errors, then move file to new location storing the path, count var, and extenstion
                   if(move_uploaded_file($_FILES['img']["tmp_name"][$f], $storage_path.test.$count.$extension))
                       $file_path = $storage_path.test.$count.$extension;
                       $count++;

                   //Insert File path into database
                   $insert = $db->prepare('INSERT INTO img (src) VALUES (:file_path)');
                   $insert -> bindParam(':file_path', $file_path);
                   $insert -> execute();

                                   //Pull RowID for use in song_img relationship table
                /*   $lastimg = $db->lastInsertId();

                                   //Insert into relationship table
                   $relationship = $db -> prepare('INSERT INTO song_img (song_id, img_id) VALUES (:song, :img)');
                   $relationship -> bindParam(':song', $song_id);
                   $relationship -> bindParam(':img', $lastimg);
                   $relationship -> execute();
*/              
 }//End else
        }//End if (files error)
    }//End for each
}//End if(isset)

