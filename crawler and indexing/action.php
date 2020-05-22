<?php

 # Remove leading and trailing spacing.

 $pagenum = trim( $_POST["pagenum"] );
 $URL     = trim( $_POST["URL"] );
 $viewKeyword     = trim( $_POST["viewKeyword"] );

 # For security, remove some Unix metacharacters.
 $meta    = array( ";", ">", ">>", "<", "*", "?", "&", "|" );
 //$meta    = array( ">", ">>", "<", "*", "&", "|" );
 $pagenum = str_replace( $meta, "", $pagenum );
 //$URL     = str_replace( $meta, "", $URL );
 //$newUrl     = str_replace( $meta, "", $newUrl );
 $viewKeyword   = str_replace( $meta, "", $viewKeyword );


  if ( $_POST['act'] == "Submit" ){

   if ( $_POST['radio'] == "url_title" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  listTitle.php  $viewKeyword" );
   }
    elseif ( $_POST['radio'] == "keyword" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  listKeyword.php $viewKeyword" );
   }
    elseif ( $_POST['radio'] == "url_description" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  listDescription.php $viewKeyword" );
   }
   elseif ( $_POST['radio'] == "all" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  list.php  " );
   }
 }

elseif ( $_POST['act'] == "Insert Web Index" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  spider.php $pagenum  $URL" );
   }

elseif ( $_POST['act'] == "View Web Index" ) {
   header( "Content-type: text/html" );
   system( "/usr/bin/php  view.html" );
 }

 elseif ( $_POST['act'] == "Clear System" ) {
   header( "Content-type: text/plain" );
   system( "/usr/bin/php  clear.php" );
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
