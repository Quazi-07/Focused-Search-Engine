<?php

# include database connection file
 include 'connect.php';
# query to select everything from all tables
$viewKeyword = $argv[1];
 $no   = 1;

# query to select everything based on keywords matched with url description
$keywords=explode(',',$viewKeyword);
if(!empty($keywords)){
     $sql  = "SELECT distinct k.keyword, u.url, u.url_title, u.url_description FROM keyword_table k, url_info_table u, make_relation r WHERE k.kw_ID=r.kw_ID AND u.url_\
ID=r.url_ID AND u.url_description LIKE '%$keywords[0]%' ";
        if(count($keywords)>1){
        for($i=1;$i<count($keywords);$i++){
         $sql.="OR u.url_description LIKE '%$keywords[$i]%' ";
            }
       }
}
echo( $sql . "<br /><br /><br>" );
$result = $conn->query( $sql );
if ( $result->num_rows > 0 ){
  while( $row = $result->fetch_assoc( ) ) {
    if ($no >10){
      break;
          }
    else{
      echo( $no++ .": ");
      echo("<a target='_blank' href='" .$row[url] . "'>" . $row[url_title]."</a><br>" );
      echo( $row[url]."<br>" );
      echo( $row[url_description]."<br><br>" );
        }
    }
}

$conn->close( );

?>
