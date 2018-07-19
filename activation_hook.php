<?php
global $wpdb;



$table_name = "hs_att";
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  dt date NOT NULL,
  cls int(9) NOT NULL,
  prd int(9) NOT NULL,
  dcp varchar(99) NOT NULL,
  att longtext NOT NULL,
  PRIMARY KEY  (ID)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );



?>
