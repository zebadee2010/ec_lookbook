<?php 
$title = "Add Song : Elevation";
include("../doc/head.php");
include("../inc/dbcon.php");

function options($idField, $optionName, $sortField, $db)
{
	try {
		$results = $db->query("SELECT $idField, $optionName FROM $optionName ORDER BY $sortField");
	} catch (Exception $e){
		echo "Database appears to be down or missing data.";
	}
	while ($row = $results->fetch(PDO::FETCH_ASSOC)){
		?>
	<option value="<?php echo $row[$idField];?>"><?php echo $row[$optionName] ?> </option>
	<?php }
}

?>

<form id="color_form" action="../../inc/post.php" method="post" enctype="multipart/form-data">
        <input class="song_title" name="name" placeholder="Song Name"></input></br></br>

     <div id="column1" >
        <p>Select Input Value Type:</p></br>
        <label for="dmx"><input type="radio" name="toggle" value="dmx" id="dmx">DMX</label>
<label for="percent"><input type="radio" name="toggle" value="percent" id="percent" checked="true">Percent</label></br></br>
        <input class="song_entry_color" id="last" method="post" name="color[0]" placeholder="Color"></input></br>
        <input class="song_entry" name="red[0]" placeholder="Red"></input><input class="song_entry" name="green[0]" placeholder="Green"></input><input class="song_entry" name="blue[0]" placeholder="Blue"></input>
        </br></br>
    </div>


<div id="column2">
    <select class="selection" id="timing_select" name="timing_select">
        <option value="" id="null">Select Time Signature</option>
         <?php 
            options("id", "time_signature", "id", $db);
	      ?>
    </select>
    <select class="selection" id="cue_select" name="cue_select">
        <option value="" id="null">Select Fire Cue</option>
         <?php 
            options("id", "fire_cue", "id", $db);
	      ?>
    </select>
</div>
    <div id="uploads">
        <div id="img_uploads">
            <label for="img">Select Image</label></br>
            <input name="img[]" type="file" id="img">
        </div>

        
		<div id="lower_add_buttons"></div>
			<input type="button" value="Additional Color" onclick="add_new()"</input>
			<input type="button" value="Additional IMG" onclick="additional_img()"></br></br>
			<input type="submit" class="form_buttons" value="Submit" method="POST"></input></br>
		</div>
    </div>
</form>

<?php include("../doc/foot.php"); ?>
