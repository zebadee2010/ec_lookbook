<?php 
$title = "Make Weekend Set : Elevation";
include("../doc/head.php");
include("../inc/dbcon.php");

function options($idField, $optionName, $tableName, $sortField, $db)
{
	try {
		$results = $db->query("SELECT $idField, $optionName FROM $tableName ORDER BY $sortField");
	} catch (Exception $e){
		echo "Database appears to be down or missing data.";
	}
	while ($row = $results->fetch(PDO::FETCH_ASSOC)){
		?>
	<option value="<?php echo $row[$idField];?>"><?php echo $row[$optionName] ?> </option>
	<?php }
}





?>
<form id="setlist" method="post" action="../inc/setlist_process.php">
<?php $amount = $_GET['num']; ?>
	<input type="hidden" name="num" value="<?php echo $amount?>">
	<div id="column1">
		<?php

for($i=1; $i<=$amount; $i++){
?>

<h3>Song <?php echo $i?>: <select class="selection" id="song_select" name="song<?php echo $i?>">
			<option value="null" id="null">Select Song</option>
			 <?php 
				options("id", "title_visible", "song_title", "id", $db);
			  ?>
		</select>
		</h3>
<?php
}?>
	</div>
	<input type="submit">
</form>



