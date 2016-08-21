<?php
try{
    $db = new PDO("mysql:host=localhost;dbname=lighting_prod;port=3306","mysql","God0fJ@cob");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $db->exec("set NAMES 'utf8'");
}
catch (Exception $e) {
    var_dump($e->getMessage());
    exit;
}
