<?php
// Vos renseignements MySQL
$dbhost							= "localhost"; // Hôte base de données
$dbuser							= "root"; // Utilisateur base de données
$dbpass							= ""; // Mot de passe base de données
$dbname							= "sondage"; // Nom de la base de données

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to mysql");
mysql_select_db($dbname);