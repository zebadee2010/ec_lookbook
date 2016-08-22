<?php 

//Create connection

//    $db = new PDO("mysql:host=localhost;port=8889","root","password");
//    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
//    $db->exec("set NAMES 'utf8'");
include("../inc/dbcon.php");

//create database
try{
$db->query("CREATE DATABASE IF NOT EXISTS `lighting_prod` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
$db->query("USE `lighting_prod`");

//Create tables

$db->query("CREATE TABLE IF NOT EXISTS `song_title` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `title_visible` VARCHAR(45) UNIQUE NOT NULL,
        `title_hidden` VARCHAR(45) UNIQUE NOT NULL,
		`date_created` datetime NOT NULL,
  		`date_updated` datetime NOT NULL,
        PRIMARY KEY (`id`))
        ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `color` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`red_dmx` INT NOT NULL,
         	`green_dmx` INT NOT NULL,
         	`blue_dmx` INT NOT NULL,
			`red_percent` INT NOT NULL,
          	`green_percent` INT NOT NULL,
          	`blue_percent` INT NOT NULL,
            `description` VARCHAR(60) NOT NULL,
            PRIMARY KEY(`id`))
            ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `song_color` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `song_id` INT UNSIGNED NOT NULL,
            `color_id` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB");


$db->query("CREATE TABLE IF NOT EXISTS `fire_cue` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `fire_cue` VARCHAR(120) NULL,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `time_signature` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `time_signature` VARCHAR(45) NOT NULL,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `song_parameters` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `song_id` INT UNSIGNED NOT NULL,
            `fire_cue` VARCHAR(60) NOT NULL,
            `time_signature` VARCHAR(20) NOT NULL,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `img` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `src` VARCHAR(100) NOT NULL,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `song_img` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `song_id` INT UNSIGNED NOT NULL,
            `img_id` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `weekly_segments` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`segment_name` VARCHAR(60) NOT NULL,
			`notes` TEXT NOT NULL,
			PRIMARY KEY (`id`))
			ENGINE = InnoDB");

$db->query("CREATE TABLE IF NOT EXISTS `setlist` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`song_id` INT UNSIGNED NOT NULL ,
			PRIMARY KEY (`ID`))
			ENGINE = InnoDB");



//Add foreign keys

$db->query("ALTER TABLE `song_img`
            ADD CONSTRAINT FK_img_id
            FOREIGN KEY (img_id) REFERENCES img(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE");

$db->query("ALTER TABLE `song_img`
            ADD CONSTRAINT FK_song_img_id
            FOREIGN KEY (song_id) REFERENCES song_title(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE");

$db->query("ALTER TABLE `song_color`
            ADD CONSTRAINT FK_song_id
            FOREIGN KEY (song_id) REFERENCES song_title(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE");

$db->query("ALTER TABLE `song_color`
            ADD CONSTRAINT FK_color_id
            FOREIGN KEY (color_id) REFERENCES color(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE");

$db->query("ALTER TABLE `song_parameters`
            ADD CONSTRAINT FK_song_parameters
            FOREIGN KEY (song_id) REFERENCES song_title(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE");

$db->query("ALTER TABLE `setlist`
	    ADD CONSTRAINT FK_setlist_song_id
	    FOREIGN KEY (song_id) REFERENCES song_title(id)
	    ON UPDATE CASCADE
            ON DELETE CASCADE");

}catch(Exception $e){
	echo "Something went wrong while creating the tables.";
}
include ("setup.php");

?>
