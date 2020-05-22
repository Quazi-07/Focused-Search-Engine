<?php

 # Remove leading and trailing spacing.
 #$rootnum = trim( $_POST["rootnum"] );
 //$pagenum = trim( $_POST["pagenum"] );
 $viewKeyword     = trim( $_POST["viewKeyword"] );

 # For security, remove some Unix metacharacters.
 $meta    = array( ";", ">", ">>", "<", "*", "?", "&", "|" );
 #$rootnum = str_replace( $meta, "", $rootnum );
 //$pagenum = str_replace( $meta, "", $pagenum );
 $viewKeyword   = str_replace( $meta, "", $viewKeyword );


  if ( $_POST['act'] == "Search" ){

   if ( $_POST['radio'] == "viewKeyword" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  searchRank.php  $viewKeyword" );
   }

   elseif ( $_POST['radio'] == "all" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  searchAll.php  " );
   }
 }
elseif ( $_POST['act'] == "View Code" ) {
   header( "Content-type: text/plain" );
   foreach (glob("code.txt") as $filename) {
   readfile($filename);
}

 }
 else {
   header( "Content-type: text/html" );
   echo( "<html><body>" );
   echo( "<h3>No such option: " . $_POST["act"] . "</h3>" );
   echo( "</body></html>" );
 }

?>
