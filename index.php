<?php 
$title = "Looks DB:Elevation";
include("inc/dbcon.php");
include("doc/head.php");
?>

<div id="song_thumbs" >
    <ul>
        <?php 
            try{
		//Get setlist
		$setlist = $db->query('SELECT setlist.id,setlist.song_id,song_title.id,song_title.title_visible FROM setlist,song_title WHERE song_title.id = setlist.song_id ORDER BY setlist.id');
				
                //$songs = $db ->query('SELECT  id,title_visible FROM song_title ORDER BY id');
                while ($row = $setlist->fetch(PDO::FETCH_ASSOC)){
					
					$title = $row['title_visible'];
					$ref = $row['id'];
					
					?>
		
					<li>
                        <a href="doc/view.php?songid=<?php echo $ref;?>">
							<?php	
					//Find images for song
                    $img = $db -> query('SELECT img_id FROM song_img WHERE song_id = ' . $ref);
                    $img_id = $img -> fetch(PDO::FETCH_ASSOC);
					
					//268x178 dimenssions
					
					if ($img_id != FALSE){
						foreach ($img_id as $img){
							$get_img = $db->query('SELECT src FROM img WHERE id = '. intval($img) . '');
							$img_src = $get_img -> fetchColumn();
							?>
							<img src=<?php echo $img_src; ?>>
							<?php
						}//End foreach
					}else{
						?>
							<img src="../images/no_image.jpg">
							<?php
					}
					
				?></br>
                                <p id="thumb">
                                    <?php
                                    echo $title;
                                    ?>
                                </p>
					
                            
                        </a>
                    </li>
				
                    
                             
            <?php            
                    
                }//End While Loop
            }catch (Exception $e){
                echo "couldn't select songs";
                }
            ?>

    </ul>
</div>


<?php include("doc/foot.php"); ?>
