<?php

# include database connection file
 include 'connect.php';
# query to select everything from all tables
$viewKeyword = $argv[1];
 $no   = 1;

# query to select everything based on keywords matched with url description
$keywords=explode(',',$viewKeyword);
if(!empty($keywords)){
     $sql  = "SELECT distinct k.keyword, u.url, u.url_title, u.url_description,";
       if(count($keywords)==1){
        $sql .= " MATCH(u.url_title) AGAINST ('$keywords[0]";
        }
        else {
         $sql .= " MATCH(u.url_title) AGAINST ('$keywords[0]";
        for($i=1;$i<count($keywords);$i++){
         $sql.=" $keywords[$i]";
         }}
    $sql .= " 'IN BOOLEAN MODE)as tscore,";
       if(count($keywords)==1){
        $sql .= "MATCH(u.url_description) AGAINST ('$keywords[0]";
        }
        else {
         $sql .= "MATCH(u.url_description) AGAINST ('$keywords[0]";
        for($i=1;$i<count($keywords);$i++){
         $sql.=" $keywords[$i]";
         }}
   $sql .= " 'IN BOOLEAN MODE)as dscore, ";
       if(count($keywords)==1){
        $sql .= " MATCH(k.keyword) AGAINST ('$keywords[0]";
        }
        else {
         $sql .= " MATCH(k.keyword) AGAINST ('$keywords[0]";
        for($i=1;$i<count($keywords);$i++){
         $sql.=" $keywords[$i]";
         }}
   $sql .= " 'IN BOOLEAN MODE)as kscore ";
    $sql .= " FROM keyword_table k, url_info_table u, make_relation r WHERE k.kw_ID=r.kw_ID AND u.url_ID=r.url_ID AND MATCH(u.url_title) ";
        if(count($keywords)==1){
        $sql .= " AGAINST ('$keywords[0]' IN BOOLEAN MODE)";
        }
        else {
         $sql .= " AGAINST ('$keywords[0]";
        for($i=1;$i<count($keywords);$i++){
         $sql.=" $keywords[$i]";
         }
         $sql .= " 'IN BOOLEAN MODE) ";
       }
//}
   $sql .= " OR MATCH(u.url_description)";
         if(count($keywords)==1){
        $sql .= " AGAINST ('$keywords[0]' IN BOOLEAN MODE)";
        }
        else {
         $sql .= " AGAINST ('$keywords[0]";
        for($i=1;$i<count($keywords);$i++){
         $sql.=" $keywords[$i]";
         }
         $sql .= " 'IN BOOLEAN MODE)";
 }
 $sql .= " OR MATCH(k.keyword)";
         if(count($keywords)==1){
        $sql .= " AGAINST ('$keywords[0]' IN BOOLEAN MODE)";
        }
        else {
         $sql .= " AGAINST ('$keywords[0]";
        for($i=1;$i<count($keywords);$i++){
         $sql.=" $keywords[$i]";
         }
         //$sql .= " 'IN BOOLEAN MODE)";
         $sql .= " 'IN BOOLEAN MODE) ";
}
$sql .= " ORDER BY (tscore+dscore+kscore) DESC";
       }
//}
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
      echo "Rank Value: ".$row[tscore]."+".$row[dscore]."+".$row[kscore]." = ".($row[tscore]+$row[dscore]+$row[kscore])."<br>";
      echo( $row[url]."<br>" );
      echo( $row[url_description]."<br><br>" );
        }
    }
}

$conn->close( );

?>
