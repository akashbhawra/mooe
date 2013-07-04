<?php
include('config.php');

  encode();
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);

  // function def. for making connection to particular db.
  function connect($db){

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if(! $conn ){
     die('Could not connect: ' . mysql_error());
    }
    else {
      // print 'Connection Established';
      mysql_select_db($db);
      return $conn;
    }
  }
//function def. fetching data from particular table of the selected db.
function fetch($field,$table){
  $database = 'jsondb';
  $conn1 = connect($database);
  $req = array();
  $arrre= array();
  foreach($field as $keys){
    $arrre[] = $keys;
  }
 // print_r($arrre[1]);die();
  $result = mysql_query('select`'.$arrre[0].",".$arrre[1].'from`'.$table, $conn1);
 $result = mysql_query('select name as name,body as body from`'.$table, $conn1);

  if (!$result) {
    echo "Could not successfully run query from DB: " . mysql_error();
  }

  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    $req[] = $row;
  }
  return $req;
}

//function def.  encoding and decoding in JSON .
function encode(){
   $field = array("name","body");
 // $field = "mail";
  //print_r ($field);die();
  $table = "jsontable";
  $arr = fetch($field, $table);
  $json = json_encode($arr);
  echo $json;
}


?>
