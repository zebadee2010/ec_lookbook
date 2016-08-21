<?php
try{
	$db = new PDO("mysql:host=localhost;dbname=test;port=8889","mysql","God0fJ@cob");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $db->exec("set NAMES 'utf8'");
}
catch (Exception $e) {
    var_dump($e->getMessage());
    exit;
}

$stmt = $db->query("UPDATE `table` SET row=4 WHERE id=1");
$stmt->execute();
