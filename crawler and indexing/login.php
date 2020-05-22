<?php

# Remove leading and trailing spacing.
 $u_name = trim( $_POST["u_name"] );
 $log_password  = trim( $_POST["log_password"] );

 # For security, remove some Unix metacharacters.
 $meta    = array( ";", ">", ">>", "<", "*", "?", "&", "|" );
 $u_name = str_replace( $meta, "", $u_name );
 $log_password     = str_replace( $meta, "", $log_password );

#if ($_POST['u_name'] == "saif07" AND $_POST['log_password']=="mrahman5138"){
if ($u_name == "saif07" AND $log_password=="mrahman5138"){

  // echo "<br><a href='http://undcemcs02.und.edu/~mdsaifur.rahman.1/515/1/index2.html'>Click to Visit UR(!) Search Engine</a>";
    header("location:index2.html");
}
 else
  echo "<br>Wrong login info!"

?>

