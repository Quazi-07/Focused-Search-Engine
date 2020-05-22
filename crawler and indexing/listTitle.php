<?php

# include database connection file
 include 'connect.php';

 $viewKeyword = $argv[1];
 $no   = 1;
 
 # query to select everything based on keywords matched with url title
$keywords=explode(',',$viewKeyword);
if(!empty($keywords)){
     $sql  = "SELECT distinct k.keyword, u.url, u.url_title, u.url_description FROM keyword_table k, url_info_table u, make_relation r WHERE k.kw_ID=r.kw_ID AND u.url_ID=r.url_ID AND u.url_title LIKE '%$keywords[0]%' ";
        if(count($keywords)>1){
        for($i=1;$i<count($keywords);$i++){
         $sql.="OR u.url_title LIKE '%$keywords[$i]%' ";
            }
       }
}


 $no   = 1;
 echo( $sql . "<br /><br />" );
 echo( "<center><table  width='96%' border='1' cellspacing='1' cellpadding='3' color='navy' class='shadow'>" );
 echo( "<tr><th width='03%'>No.</th>" );
 echo( "<th width='20%'>Keyword</th>" );
 echo( "<th width='9%'>URL</th>" );
 echo( "<th width='14%'>Title</th>" );
 echo( "<th width='50%'>Description</th></tr>" );

 $result = $conn->query( $sql );
 if ( $result->num_rows > 0 )
  while( $row = $result->fetch_assoc( ) ) {
    echo( "<tr><td>". $no++ . "</td><td>" . $row[keyword] );
    echo( "</td><td><a target='_blank' href='" . $row[url] . "'>" . $row[url] );
    echo( "</a></td><td>" . $row[url_title] );
    echo( "</td><td>" . $row[url_description] . "</td></tr>" );
  }
 echo( "</table>" );

 $conn->close( );

?>
