<?php
// These info are for database connection setup
 $password = "mrahman5138";
 $username = "mdsaifur.rahman.1@undcsmysql";
 $database = "mdsaifur_rahman_1";
 $host     = "undcsmysql.mysql.database.azure.com";
 $conn     = new mysqli( $host, $username, $password, $database );

 if ( $conn->connect_error )
   die( 'Could not connect: ' . $conn->connect_error );
 else
   echo("Database connection successful<br>" );

?>
