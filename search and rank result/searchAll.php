<?php

# include database connection file
 include 'connect.php';
# query to select everything from all tables
$no   = 1;
$sql  = "SELECT  u.url_title,u.url, u.url_description FROM keyword_table k, url_info_table u, make_relation r WHERE ";
$sql .= "k.kw_ID=r.kw_ID AND u.url_ID=r.url_ID;";
echo( $sql . "<br /><br /><br><br>" );

$result = $conn->query( $sql );
if ( $result->num_rows > 0 )
  while( $row = $result->fetch_assoc( ) ) {
    echo( $no++ .": ");
    echo("<a target='_blank' href='" .$row[url] . "'>" . $row[url_title]."</a><br>" );
    echo( $row[url]."<br>" );
    echo( $row[url_description]."<br><br>" );
  }

$conn->close( );

?>
