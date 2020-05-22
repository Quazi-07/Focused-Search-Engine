<?php
 include 'connect.php';

$no   = 1;
$sql  = "SELECT k.keyword, u.url, u.url_title, u.url_description FROM keyword_table k, url_info_table u, make_relation r WHERE ";
$sql .= "k.kw_ID=r.kw_ID AND u.url_ID=r.url_ID;";
echo( $sql . "<br /><br />" );
echo( "<center><table style='width:100%;table-layout: fixed;overflow:hidden;'  border='1' cellspacing='1' cellpadding='3' color='navy' class='shadow'>" );
echo( "<tr><th style='width:3%;'>No.</th>" );
echo( "<th  style='width:40%;'>Keyword</th>" );
echo( "<th  style='width:20%;'>URL</th>" );
echo( "<th  style='width:15%;'>Title</th>" );
echo( "<th  style='width:22%;'>Description</th></tr>" );

$result = $conn->query( $sql );
if ( $result->num_rows > 0 )
  while( $row = $result->fetch_assoc( ) ) {
    echo( "<tr><td style = 'overflow:hidden;'>". $no++ . "</td><td style = 'overflow:hidden;'>" . $row[keyword] );
    echo( "</td><td style = 'overflow:hidden;'><a target='_blank' href='" . $row[url] . "'>" . $row[url] );
    echo( "</a></td><td style = 'overflow:hidden;'>" . $row[url_title] );
    echo( "</td><td style = 'overflow:hidden;'>" . $row[url_description] . "</td></tr>" );
  }
echo( "</table>" );

$conn->close( );

?>
