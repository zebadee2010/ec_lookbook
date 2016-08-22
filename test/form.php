<?php
try{
include("../inc/dbcon.php");
}
catch (Exception $e) {
    var_dump($e->getMessage());
    exit;
}

$stmt = $db->query("UPDATE `table` SET row=4 WHERE id=1");
$stmt->execute();
