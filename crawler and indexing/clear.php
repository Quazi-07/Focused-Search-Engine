<?php

# include database connection file
 include 'connect.php';

# Delete all data from make_relation table
$sql = "DELETE FROM make_relation;";
echo( $sql . "\n" );
if ( $conn->query( $sql ) == TRUE )
  echo "Table make_relation deleted successfully\n\n";
else
  echo "Error deleting table: " . $conn->error;

# Delete all data from url_info_table table
$sql = "DELETE FROM url_info_table;";
echo( $sql . "\n" );
if ( $conn->query( $sql ) == TRUE )
  echo "Table url_info_table deleted successfully\n\n";
else
  echo "Error deleting table: " . $conn->error;

# Delete all data from keyword_table table
$sql = "DELETE FROM keyword_table;";
echo( $sql . "\n" );
if ( $conn->query( $sql ) == TRUE )
  echo "Table keyword_table deleted successfully";
else
  echo "Error deleting table: " . $conn->error;

# close the database connection
$conn->close( );

?>

