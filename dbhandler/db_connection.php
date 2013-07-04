<?php
include('config.php');
require_once 'QueryBuilder.php';

function db_connect($db){
 $dbhost = DB_NAME;
  $dbuser = DB_USER;
  $dbpass = DB_PASS;
  $conn = mysql_connect($dbhost, $dbuser, $dbpass);
  if(! $conn ){
   die('Could not connect: ' . mysql_error());
  }
  else
  // print 'Connection Established';
  mysql_select_db($db);
  return $conn;
}

/* Query Builder function creates a query builder function */
function querybuilder() {
 $query_builder = QueryBuilder::create();
 $query = QueryBuilder::create()->select('*')->from('table');
 print $query;
}

$test = querybuilder();

echo $test;
die();
