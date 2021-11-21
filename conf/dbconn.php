<?php
// $dbhost = "localhost";
// $dbuser = "root";
// $dbpwd = "mpark9698!";
// $db = "duvie";
$dbhost = "127.0.0.1:3307";
$dbuser = "root";
$dbpwd = "root12";
$db = "test";
$dbconn = mysqli_connect($dbhost, $dbuser, $dbpwd, $db);
mysqli_set_charset($dbconn, "utf8");
// mysqli_query($this->dbconn, $qry );
// mysqli_fetch_array( $this->getRequest($qry), MYSQLI_ASSOC );
?>
