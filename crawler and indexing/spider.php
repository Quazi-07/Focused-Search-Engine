<?php
 include 'connect.php';

 $pagenum = $argv[1];
 $URL      = $argv[2];

# Dump the source code to the file result.txt.
 $cmd = "lynx -dump -source ". $URL ." > result.txt";
 system( $cmd );

 # Find the page title by using a regular expression.
 $file    = file_get_contents( "result.txt" );
 $pattern = '/<title>.*?<\/title>/';
 preg_match( $pattern, $file, $matches );
 $url_title   = strip_tags( $matches[0] );

# Find the page keyword by using a regular expression.
 $file    = file_get_contents( "result.txt" );
 preg_match('/<meta.*? name=("|\')keywords("|\').*? content=("|\')(.*?)("|\')/i', $file, $matches);
 $keyword   = strip_tags( $matches[4] );

# Find the page description by using a regular expression.
 $file    = file_get_contents( "result.txt" );
 preg_match('/<meta.*? name=("|\')description("|\').*? content=("|\')(.*?)("|\')/i', $file, $description);
 $url_description   = strip_tags( $description[4] );

 # Find the ID of the input keyword from the keywords table.
 $sql = "SELECT kw_ID FROM keyword_table WHERE keyword='$keyword';";
 //echo("<br>". $sql . "<br>" );
 $result = $conn->query( $sql );
 if ( $result->num_rows > 0 )
   while( $row = $result->fetch_assoc( ) )
     $kw_ID = $row['kw_ID'];
 else {
   $sql = "INSERT INTO keyword_table( keyword ) VALUES ( '$keyword' );";
   //echo( $sql . "<br>" );
   $conn->query( $sql );
   $sql = "SELECT kw_ID FROM keyword_table WHERE keyword='$keyword';";
   //echo( $sql . "<br>" );
   $result = $conn->query( $sql );
   if ( $result->num_rows > 0 )
     while( $row = $result->fetch_assoc( ) )
       $kw_ID = $row['kw_ID'];
 }
//echo "KWID".$kw_ID."<br>";

 # Find the ID of the input URL from the url_title table.
 $sql = "SELECT url_ID FROM url_info_table WHERE url='$URL';";
 //echo( $sql . "<br>" );
 $result = $conn->query( $sql );
 if ( $result->num_rows > 0 )
   while( $row = $result->fetch_assoc( ) )
     $url_ID = $row['url_ID'];
 else {
   $sql = "INSERT INTO url_info_table( url, url_title, url_description ) VALUES ( '$URL', '$url_title', '$url_description' );";
   //echo( $sql . "<br>" );
   $conn->query( $sql );
   $sql = "SELECT url_ID FROM url_info_table WHERE url='$URL';";
   //echo( $sql . "<br>" );
   $result = $conn->query( $sql );
   if ( $result->num_rows > 0 )
     while( $row = $result->fetch_assoc( ) )
       $url_ID = $row['url_ID'];
 }
//echo "URLID:".$url_ID."<br>";

 # Update the inverted list if the keyword is found.
  // echo "KWID:".$kw_ID."<br>";
   $sql = "INSERT INTO make_relation( kw_ID,url_ID ) VALUES ( $kw_ID, $url_ID );";
   //echo(  $sql . "<br>" );
   $conn->query( $sql );

# List all root urls in a file named urls.txt

 $cmdList = "lynx -listonly -dump '". $URL ."' > rootUrls.txt";
 system( $cmdList );

# Find all url links by using a regular expression.
 $file = file_get_contents( "rootUrls.txt" );
 $pattern = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
 //$pattern = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]\/?/i';
 preg_match_all( $pattern, $file, $matches );
 $allRootdup = $matches[0];
 $allRoot = array_unique($allRootdup);
 $root = count($allRoot)+1;
 print_r("<br>Total Root  URL count(without duplicates):".$root."");

$allFilteredRoot = array_filter($allRoot,function($v){
                                global $URL;
                              return strpos($v,$URL)===0;});
//print_r("<br>Total Root  URL count(root):".count($allFilteredRoot)."");
file_put_contents('allUrls.txt', var_export($allFilteredRoot, TRUE),FILE_APPEND);

for ($r = 0; $r < count($allRoot); $r++) {

 $childUrl = $allRoot[$r];
 //$childUrl = strtok($childUrl1, '?');
 $cmd = "lynx -listonly -dump '". $childUrl ."' >> childUrls.txt";
 system( $cmd );
}

 $file2 = file_get_contents( "childUrls.txt" );
 $patternChild = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
 //$patternChild = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]\/?/i';
 preg_match_all( $patternChild, $file2, $matches2 );
 $allChilddup = $matches2[0];
 $allChild = array_unique($allChilddup);
 $totalChild = count($allChild);
 print_r("<br>Total URL count(BFS):".$totalChild."<br>");

$allFilteredChild = array_filter($allChild,function($v){
                                global $URL;
                              return strpos($v,$URL)===0;});
//print_r("<br>Total URL count(BFS-2):".count($allFilteredChild)."<br>");
file_put_contents('allUrls.txt', var_export($allFilteredChild, TRUE),FILE_APPEND);

// print_r("<br>Total URL count(BFS-2):".count($allFilteredChild)."<br>");
for ($c = 0; $c < count($allFilteredChild); $c++) {

 $grandUrl = $allFilteredChild[$c];
 //$grandUrl = strtok($grandUrl1, '?');
 $cmd = "lynx -listonly -dump '". $grandUrl ."' >> grandUrls.txt";
 system( $cmd );
}

 $file4 = file_get_contents( "grandUrls.txt" );
 $patternGrand = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
 //$patternGrand = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]\/?/i';
 preg_match_all( $patternGrand, $file4, $matches4 );
 $allGranddup = $matches4[0];
 $allGrand = array_unique($allGranddup);

$allFilteredUrl = array_filter($allGrand,function($v){
                                global $URL;
                              return strpos($v,$URL)===0;});
//$num = count($allFilteredUrl);
//echo "Based on seed URL total match found:".$num."<br><br>";


 file_put_contents('allUrls.txt', var_export($allFilteredUrl, TRUE),FILE_APPEND);

 $file3 = file_get_contents( "allUrls.txt" );
 $pattern = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
 //$pattern = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]\/?/i';
 preg_match_all( $pattern, $file3, $matches3 );
 $allUrldup = $matches3[0];
 $allUrldiff = array_unique($allUrldup);
$allUrl = array_values($allUrldiff);
//print_r($allUrl);
 $num = count($allUrl)+1;
 echo "Based on seed URL total match found: ".$num."<br><br>";

# index desired url one by one untill 500
 for ($i = 0; $i < $pagenum+1 ; $i++) {

    if ($i > 500 ){
      echo "\nInserting web index is going to stop here (cross the maximum limit 500) ";
      break;
      }
    else {
      // if ($num<$pagenum){
    $newUrl = $allUrl[$i];
    echo "<br>";
    echo $i.": ".$newUrl;
    echo "<br>";
    #echo count($allUrl);
    #echo count($newUrl);
    //break;}
//}}}
  //$meta    = array( ";", ">", ">>", "<", "*", "?", "&", "|" );
  //$newUrl = str_replace( $meta, "", $newUrl );
  //$newUrl = strtok($newUrl, '?');

 # Dump the source code to the file result.txt.
 $cmd = "lynx -dump -source ". $newUrl ." > result.txt";
 system( $cmd );

 # Find the page title by using a regular expression.
 $file    = file_get_contents( "result.txt" );
 $pattern = '/<title>.*?<\/title>/';
 preg_match( $pattern, $file, $matches );
 $url_title   = strip_tags( $matches[0] );

# Find the page keyword by using a regular expression.
 $file    = file_get_contents( "result.txt" );
 preg_match('/<meta.*? name=("|\')keywords("|\').*? content=("|\')(.*?)("|\')/i', $file, $matches);
 $keyword   = strip_tags( $matches[4] );

# Find the page description by using a regular expression.
 $file    = file_get_contents( "result.txt" );
 preg_match('/<meta.*? name=("|\')description("|\').*? content=("|\')(.*?)("|\')/i', $file, $description);
 $url_description   = strip_tags( $description[4] );

 # Find the ID of the input keyword from the keywords table.
 $sql = "SELECT kw_ID FROM keyword_table WHERE keyword='$keyword';";
 echo( $sql . "\n\n" );
 $result = $conn->query( $sql );
 if ( $result->num_rows > 0 )
   while( $row = $result->fetch_assoc( ) )
     $kw_ID = $row['kw_ID'];
 else {
   $sql = "INSERT INTO keyword_table( keyword ) VALUES ( '$keyword' );";
   echo( $sql . "\n\n" );
   $conn->query( $sql );
   $sql = "SELECT kw_ID FROM keyword_table WHERE keyword='$keyword';";
   echo( $sql . "\n\n" );
   $result = $conn->query( $sql );
   if ( $result->num_rows > 0 )
     while( $row = $result->fetch_assoc( ) )
       $kw_ID = $row['kw_ID'];
 }

 # Find the ID of the input URL from the url_title table.
 $sql = "SELECT url_ID FROM url_info_table WHERE url='$newUrl';";
 echo( $sql . "\n\n" );
 $result = $conn->query( $sql );
 if ( $result->num_rows > 0 )
   while( $row = $result->fetch_assoc( ) )
     $url_ID = $row['url_ID'];
 else {
   $sql = "INSERT INTO url_info_table( url, url_title, url_description ) VALUES ( '$newUrl', '$url_title', '$url_description' );";
   echo( $sql . "\n\n" );
   $conn->query( $sql );
   $sql = "SELECT url_ID FROM url_info_table WHERE url='$newUrl';";
   echo( $sql . "\n\n" );
   $result = $conn->query( $sql );
   if ( $result->num_rows > 0 )
     while( $row = $result->fetch_assoc( ) )
       $url_ID = $row['url_ID'];
 }

 # Update the inverted list if the keyword is found.
   $sql = "INSERT INTO make_relation( kw_ID,url_ID ) VALUES ( $kw_ID, $url_ID );";
   echo(  $sql . "<br><br>" );
   $conn->query( $sql );

}
}

echo "<br><br> Indexing Successful<br>";
if ($pagenum > $num){
echo "Total URL inserted based on seed URL(This may differ based on removing duplicates!):".$num;
}
else {
echo "Total URL inserted based on seed URL (This may differ based on removing duplicates!):".$pagenum;
}

file_put_contents("allUrls.txt", "");
file_put_contents("grandUrls.txt", "");
file_put_contents("childUrls.txt", "");
file_put_contents("rootUrls.txt", "");

 $conn->close( );

?>
