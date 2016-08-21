<?php
$title = "Weekly Segments";
include("../doc/head.php");
include("../inc/dbcon.php");


$retrieve = $db->query('SELECT * FROM weekly_segments');
?> 
<form name="weekly"  method="post" action="../inc/weekly.php">
	<div id="weekly"><?php
while($row = $retrieve->fetch(PDO::FETCH_ASSOC)){
  ?>Edit <?php echo $row['segment_name'].'?';?>  <input type="checkbox" name="<?php echo $row['id']?>" value="<?php echo $row['segment_name']?>">
<textarea id="<?php echo $row['id']?>" name="<?php echo $row['segment_name']?>" rows="4" cols="50">
<?php echo $row['notes'];?>
	</textarea>
	<?php

}

?>
	</div>
	<input type="button" value="New Segment" onclick="new_segment()"</input></br>
	<input type="submit">
</form>