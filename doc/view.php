<?php 
include("../inc/dbcon.php");


if (isset($_GET['songid'])){
	$id = intval($_GET['songid']);
	$stmt = $db->prepare("SELECT title_visible FROM song_title WHERE id = :id");
	//$stmt->bindParam(':id', $id);
	$stmt->execute(array(':id' => $id));
	$row = $stmt->fetchColumn();
	$title = $row.": Elevation Church";
}else {$title = "Error: Elevation Church";}

include("../doc/head.php");


function get_row($tableField, $tableName, $db, $song_id)
{
	try {
		$results = $db->query("SELECT $tableField FROM $tableName WHERE song_id = $song_id");
	} catch (Exception $e){
		echo "Database appears to be down or missing data.";
	}
	while ($row = $results->fetch(PDO::FETCH_ASSOC)){
		$fieldData = $db->query("SELECT $tableField FROM $tableField WHERE id = $row[$tableField]");
		echo "<strong>".ucfirst($tableField).":</strong>" . $fieldData->fetchColumn() . "</br>";
		}
}
?>
<a href="edit.php?songid=<?php echo $id;?>">Edit song</a>
	<p id="title">
		<?php 
			//$return_title = $db->query('SELECT title_visible FROM song_title WHERE id = '.$id.'');
			//$title = $return_title->fetchColumn();
			echo ucwords($row);
		?>
	</p>
			<div id="param">
				<?php
					get_row("fire_cue", "song_parameters", $db, $id);
					get_row("time_signature", "song_parameters", $db, $id);
?>
			</div>
<?php
try{
				$return_colors = $db->query('SELECT description,red_percent,green_percent,blue_percent,red_dmx,green_dmx,blue_dmx
											FROM song_color,color
											WHERE song_id = '. $id .' AND 
											song_color.color_id = color.id');

			}catch (Exception $e){
				echo "unable to load colors";
			}
?>
			<div id="colors">
				<?php
					while($row = $return_colors->fetch(PDO::FETCH_ASSOC)){
							?><p id="description" style="color:rgb(<?php echo intval($row['red_dmx']). ',' . intval($row['green_dmx']). ',' . intval($row['blue_dmx'])?>);">
								<?php echo $row['description'];?>:  R: <strong><?php 
								echo $row['red_percent']. ' '; ?></strong> G: <strong><?php
								echo $row['green_percent']. ' ';?></strong> B: <strong><?php
								echo $row['blue_percent']. ' ';?></strong></br>
							  </p>
							<?php
						}
				?>
			</div>
	
	
	<?php 
	try{    
		$img = $db -> query('SELECT img_id FROM song_img WHERE song_id = ' . $id . '');
		$img_id = $img -> fetchAll(PDO::FETCH_NUM);

		foreach ($img_id as $img){
		$get_img = $db->query('SELECT src FROM img WHERE id = '. intval($img[0]) . '');
		$img_src = $get_img -> fetchColumn();

		//Start HTML?>
	
		<img id="preview" src=<?php echo $img_src;?> ></br></br>
		
		<?php //End HTML
    	}//End foreach
    
	}catch (Exception $e){
		echo "Song had no pictures."; //For uture use, if can't display or find pictures, show color swatches.
	}
